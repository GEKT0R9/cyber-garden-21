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

    app.get("/dashboard", (req, res, next) => {
        res.render(path.join(__dirname, "view", "dashboard.pug"));
    });

    async function handleApi(req, res, next, apiFunction) {
        try {
            res.send({
                ok: true,
                result: await apiFunction()
            });
        } catch (e) {
            const code = `${ Date.now() }-${ Math.randomInt(1, 1000) }`;
            console.error(`[api]`, "error", code, e);
            return res.status(500).send({
                ok: false,
                error: e.message.startsWith("API error") ? e.message : "Internal error",
                trackCode: code
            });
        }
    }

    app.get("/api/get", async (req, res, next) => {
        await handleApi(req, res, next, async () => {
            const date = Date.sure(req.query.date) || new Date();
            if (!date)
                throw new Error("API error: incorrect \"date\"");
            if (!req.query.group)
                throw new Error("API error: incorrect \"group\"");
            const scheduleInfo = await Schedule.getSchedule(req.query.group);
            scheduleInfo.date = date.toDateString();
            scheduleInfo.weekNumber = await Schedule.getWeekNumber(date) + 1;
            scheduleInfo.dayNum = await Schedule.dateToWeekDay(date);
            scheduleInfo.dayTitle = [ "понедельник", "вторник", "среда", "четверг", "пятница", "суббота", "воскресенье" ][scheduleInfo.dayNum - 1];
            return scheduleInfo;
        });

        function rnd() {
            return {
                region: "60000000000"
            };
        }
    });

    app.listen(process.env.PORT || 80, () => {
        console.log(`weblog Web server live on port ${ process.env.PORT || 80 }`);
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
