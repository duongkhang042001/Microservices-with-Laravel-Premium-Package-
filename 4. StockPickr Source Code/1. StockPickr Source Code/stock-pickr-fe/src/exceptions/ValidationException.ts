import Exception from "./Exception";

export default class ValidationException extends Exception {
    toString(): string {
        if (!this.data) {
            return this.message;
        }

        return Object.entries(this.data)
            .flatMap((arr: Array<string>) => arr.slice(1).join(' '))
            .join(' ');
    }
}
