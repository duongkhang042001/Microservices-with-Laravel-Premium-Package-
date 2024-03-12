import { Nullable } from "@/global";

export default abstract class Exception implements Error {
    public name = 'Exception';
    public message: string;
    public data: Nullable<object>;

    constructor (message: string, data: Nullable<object> = null) {
        this.message = message;
        this.data = data;
    }

    public abstract toString(): string;
}
