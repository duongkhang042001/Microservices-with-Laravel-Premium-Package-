import { Nullable } from "@/global";
import Exception from "./Exception";

export default class UnknownException extends Exception {
    constructor (message: string, data: Nullable<object> = null) {
        super(message, data);
        this.name = 'UnknownException';
    }

    toString(): string {
        return 'Sorry about that, please try again later...';
    }
}
