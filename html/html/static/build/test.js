"use strict";
var People = /** @class */ (function () {
    function People(name, age) {
        this.name = name;
        this.age = age;
    }
    People.prototype.info = function () {
        console.log(this.name + String(this.age));
    };
    return People;
}());
var daiki = new People('γγΎγ γ', 20);
