import toastr from 'toastr';

export default class ToastService {
    public succes(message: string, title: string | undefined): void {
        toastr.success(message, title);
    }
}
