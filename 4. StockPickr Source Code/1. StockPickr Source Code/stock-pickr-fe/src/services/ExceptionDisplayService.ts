import Exception from '@/exceptions/Exception';
import toastr from 'toastr';

export default class ExceptionDisplayService {
    display(err: any) {
        if (err instanceof Exception) {
            toastr.error(err.toString(), err.message);
        } else {
            toastr.error('Sorry about that, please try again later...', 'Something bad happened');
        }
    }
}
