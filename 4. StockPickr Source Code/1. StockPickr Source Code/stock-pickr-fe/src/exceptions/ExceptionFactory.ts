import { Nullable } from "@/global";
import { AxiosResponse } from "axios";
import Exception from "./Exception";
import { ExceptionTypes } from "./ExceptionTypes";
import UnknownException from "./UnknownException";
import ValidationException from "./ValidationException";

export default abstract class ExceptionFactory {
    public static createFromResponse(res: Nullable<AxiosResponse>, message = 'Something went wrong'): Exception {
        if (!res || !res.status) {
            return new UnknownException(message);
        }

        switch (res.status) {
            case 422: return new ValidationException(message, res.data?.errors);
            case 401: return new ValidationException(message, { credentials: ['Please provide valid credentials']})
            default: return new UnknownException(message);
        }
    }

    public static create(type: ExceptionTypes, message: string, data: any): Exception {
        switch (type) {
            case ExceptionTypes.Validation: return new ValidationException(message, data);
            default: return new UnknownException(message, data);
        }
    }
}
