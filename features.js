console.cdir = (...objs) =>
    console.dir(...objs, { colors: true });

console.sleep = (ms) => {
    return new Promise((resolve, reject) =>
        setInterval(resolve, ms)
    );
};

Date.prototype.addDays = function (days) {
    let date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
};

Date.prototype.getWeekDayNum = function () {
    let day = this.getDay();
    if (day === 0)
        return 7;
    return day;
};

Date.prototype.formatDate = function () {
    let diff = new Date() - this; // разница в миллисекундах

    if (diff < 1000) { // меньше 1 секунды
        return "прямо сейчас";
    }

    let sec = Math.floor(diff / 1000); // преобразовать разницу в секунды

    if (sec < 60) {
        return sec + " сек. назад";
    }

    let min = Math.floor(diff / 60 * 1000); // преобразовать разницу в минуты
    if (min < 60) {
        return min + " мин. назад";
    }

    let hours = Math.floor(diff / 60 * 60 * 1000); // преобразовать разницу в минуты
    if (hours < 24) {
        return hours + " час назад";
    }

    // отформатировать дату
    // добавить ведущие нули к единственной цифре дню/месяцу/часам/минутам
    let d = this;
    d = [
        "0" + d.getDate(),
        "0" + (d.getMonth() + 1),
        "" + d.getFullYear(),
        "0" + d.getHours(),
        "0" + d.getMinutes()
    ]
        .map(component => component.slice(-2)); // взять последние 2 цифры из каждой компоненты

    // соединить компоненты в дату
    return d.slice(0, 3).join(".") + " " + d.slice(3).join(":");
};

Date.prototype.toDateString = function () {
    let d = this.getDate();
    let m = this.getMonth() + 1;
    return `${ (d > 9 ? "" : "0") }${ d }.${ (m > 9 ? "" : "0") }${ m }.${ this.getFullYear() }`;
};

Date.prototype.to_YYYY_MM_DD = function () {
    let d = this.getDate();
    let m = this.getMonth() + 1;
    return `${ this.getFullYear() }-${ (m > 9 ? "" : "0") }${ m }-${ (d > 9 ? "" : "0") }${ d }`;
};

Date.prototype.isValid = function () {
    return !isNaN(this);
};

Date.prototype.getWeekNumber = function () {
    let d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
    let dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    let yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
};

Date.formatTime = (secs) => {
    let mins = Math.floor(secs / 60);
    let hours = Math.floor(mins / 60);
    mins %= 60;
    secs %= 60;
    let d = [
        "0" + mins,
        "0" + secs
    ];
    if (hours > 0)
        d.unshift("0" + hours);

    return d.map(c => c.slice(-2)).join(":");
};

Date.sure = (date) => {
    if (!date)
        return null;
    if (date instanceof Date && date.isValid())
        return date;
    else {
        const dateMatch = /^(?<day>[0-9]{1,2}).(?<month>[0-9]{1,2}).(?<year>[0-9]{4})$/.exec(date) ||
            /^(?<year>[0-9]{4})-(?<month>[0-9]{1,2})-(?<day>[0-9]{1,2})$/.exec(date);
        if (dateMatch === undefined || dateMatch === null)
            return null;
        let jsDate = new Date(+dateMatch.groups.year, +dateMatch.groups.month - 1, +dateMatch.groups.day);
        if (jsDate.getFullYear() !== +dateMatch.groups.year ||
            (jsDate.getMonth() + 1) !== +dateMatch.groups.month ||
            jsDate.getDate() !== +dateMatch.groups.day)
            return null;
        return jsDate;
    }
};

Math.doublePI = Math.PI * 2;
Math.PI2 = Math.PI / 2;

Math.angleDiff = (angle1, angle2) => {
    let diff = (angle2 - angle1) % Math.doublePI;
    if (diff < 0)
        diff += Math.doublePI;
    if (diff > Math.PI)
        return -(Math.doublePI - diff);
    else
        return diff;
};

Math.addVector = ([ x1, y1 ], [ x2, y2 ], angle) => {
    let d = Math.hypot(y2, x2);
    let a = Math.atan2(y2, x2);
    let x = d * Math.cos(angle + a);
    let y = d * Math.sin(angle + a);
    return [ x1 + x, y1 + y ];
};

Math.normalizeVector = ([ x, y ]) => {
    const dist = Math.hypot(x, y);
    if (dist !== 1 && dist !== 0) {
        x /= dist;
        y /= dist;
    }
    return [ x, y ];
};

Math.randomizeArray = (array) =>
    array[Math.floor(Math.random() * (array.length))];

Math.randomInt = (min, max) =>
    Math.floor(min + Math.random() * (max - min));

Math.randomFloat = (min, max) =>
    min + Math.random() * (max - min);

Math.lerp = (t, a, b) =>
    a + (b - a) * t;

Math.unLerp = (t, a = -1, b = 1) =>
    (t - a) / (b - a);

Math.clamp = (t, a = 0, b = 1) =>
    t < a ? a : t > b ? b : t;

Math.roundN = (a, n = 2) =>
    Math.round(a * (10 ** n)) / (10 ** n);

Math.easeInQuad = (t) => t ** 2;
Math.easeOutQuad = (t) => t * (2 - t);
Math.easeInOutQuad = (t) => t < .5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
Math.easeInCubic = (t) => t ** 3;
Math.easeOutCubic = (t) => (--t) * t * t + 1;
Math.easeInOutCubic = (t) => t < .5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
Math.easeInQuart = (t) => t ** 4;
Math.easeOutQuart = (t) => 1 - (--t) * t * t * t;
Math.easeInOutQuart = (t) => t < .5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t;
Math.easeInQuint = (t) => t ** 5;
Math.easeOutQuint = (t) => 1 + (--t) * t * t * t * t;
Math.easeInOutQuint = (t) => t < .5 ? 16 * t * t * t * t * t : 1 + 16 * (--t) * t * t * t * t;

Array.dedublicate = function (array) {
    return array.filter((a, b) => array.indexOf(a) === b);
};

Array.prototype.dedublicate = function () {
    return Array.dedublicate(this);
};

Array.randomItem = function (array, count = 1) {
    if (count <= 0 || !array || !Array.isArray(array)) {
        return null;
    } else if (count === 1) {
        return array[Math.floor(Math.random() * array.length)];
    } else {
        const res = [];
        for (let i = 0; i < count; i++) {
            res.push(array[Math.floor(Math.random() * array.length)]);
        }
        return res;
    }
};

Array.prototype.randomItem = function (count = 1) {
    return Array.randomItem(this, count);
};

Array.count = function (array, expression) {
    if (!expression || !array || !Array.isArray(array)) {
        return 0;
    } else {
        let count = 0;
        for (let i = 0; i < array.length; i++) {
            if (expression(array[i], i, array))
                count++;
        }
        return count;
    }
};

Array.prototype.count = function (expression) {
    return Array.count(this, expression);
};

Array.prototype.last = function () {
    return this[this.length - 1];
};

Array.prototype.first = function () {
    return this[0];
};
