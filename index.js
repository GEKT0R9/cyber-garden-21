const { Client } = require("pg");
const express = require("express");
const bodyParser = require("body-parser");
const cookieParser = require("cookie-parser");
const crypto = require("crypto");
const compression = require("compression");
const path = require("path");
require("./features.js");

const db = new Client({
    connectionString: process.env.DATABASE_URL,
    ssl: {
        rejectUnauthorized: false
    }
});

db.rquery = async (sql, params) => {
    try {
        const res = await db.query(sql, params);
        return res.rows;
    } catch (e) {
        console.error(e);
        return [];
    }
};
db.rquery1 = async (sql, params) => {
    const res = await db.rquery(sql, params);
    return res[0];
};
db.connect();

process.on("SIGINT", async (code) => {
    db.end();
    console.log(`*** About to exit with code: ${ code }`);
});

async function main() {
    const app = express();

    app.set("trust proxy", !!process.env.trustProxy);
    app.set("view engine", "pug");
    app.use(cookieParser());
    app.use(express.static("view"));
    app.use(bodyParser.urlencoded({ extended: true }));
    app.use(bodyParser.json());
    app.use(compression());
    app.use((req, res, next) => {
        req.startTime = Date.now();
        let id = req.cookies.i;
        if (!id) {
            id = checksum(`${ Date.now() }-${ Math.randomInt(0, 10000) }`);
            res.cookie("i", id, { httpOnly: true, maxAge: 10 * 365 * 24 * 60 * 60 * 1000 });
        }
        console.log(`[api] webRequest ${ req.ip }, ${ req.connection.remoteAddress }, ${ req.method }, ${ req.originalUrl }, ${ id }, ${ JSON.stringify(req.headers) }`);
        next();
    });
    app.use(async (req, res, next) => {
        if (req.cookies.aid) {
            req.user = await db.rquery1("select id, last_name, first_name, middle_name, username, email from account where id=$1", [ req.cookies.aid ]);
        }
        next();
    });

    app.use((req, res, next) => {
        if (Math.random() < 0.5)
            res.setHeader("X-Powered-By", "PHP/7.4.11");
        else
            res.setHeader("X-Powered-By", "Microsoft-IIS/10.0");
        next();
    });

    app.get("/", (req, res, next) => {
        res.render(path.join(__dirname, "view", "index.pug"));
    });

    app.get("/auth", async (req, res, next) => {
        if (req.user) {
            res.clearCookie("aid");
            res.redirect("/");
        } else {
            if (req.query.l && req.query.p) {
                const aid = await db.rquery1(`select id from account where username = $1 and password = $2`, [ req.query.l, checksum(req.query.p) ]);
                if (aid) {
                    res.cookie("aid", aid.id);
                    res.redirect("/dashboard");
                    return;
                }
            }
            res.redirect("/?err=Неверный логин или пароль");
        }
    });

    app.get("/reports", (req, res, next) => {
        if (!req.user) {
            res.redirect("/");
            return;
        }
        res.render(path.join(__dirname, "view", "reports.pug"), {
            activeTab: "reports"
        });
    });

    app.get("/dashboard", (req, res, next) => {
        if (!req.user) {
            res.redirect("/");
            return;
        }
        res.render(path.join(__dirname, "view", "dashboard.pug"), {
            activeTab: "dashboard"
        });
    });

    app.get("/accounts", (req, res, next) => {
        if (!req.user) {
            res.redirect("/");
            return;
        }
        res.render(path.join(__dirname, "view", "accounts.pug"), {
            activeTab: "accounts"
        });
    });

    app.listen(process.env.PORT || 80, () => {
        console.log(`weblog Web server live on port http://127.0.0.1:${ process.env.PORT || 80 }/`);
    });
}

function checksum(string, hashName = "md5") {
    const hash = crypto.createHash(hashName);
    hash.setEncoding("hex");
    hash.write(string);
    hash.end();
    return hash.read();
}

main()
    .catch(e => console.error(e));
