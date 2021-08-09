class People {
    private name:string;
    private age:number;

    constructor(name:string, age:number) {
        this.name = name;
        this.age = age;
    }

    info() {
        console.log(this.name + String(this.age));
    }
}

const daiki = new People('やまだい', 20);