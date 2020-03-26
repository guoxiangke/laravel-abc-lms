https://stackoverflow.com/questions/58078757/class-illuminate-support-facades-input-not-found
https://kristijanhusak.github.io/laravel-form-builder/form/methods-and-properties.html#getrequest

I have an error when upgrading laravel 6

Symfony \ Component \ Debug \ Exception \ FatalThrowableError (E_ERROR) Class 'Illuminate\Support\Facades\Input' not found

if you're using less version of Laravel 5.2

'Input' => Illuminate\Support\Facades\Input::class,
Or You can import Input facade directly as required,

use Illuminate\Support\Facades\Input;
In Laravel 5.2 Input:: is replaced with Request::

use

Request::
Add to the top of Controller or any other Class

use Illuminate\Http\Request;