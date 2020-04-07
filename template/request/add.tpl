<?php
namespace {{namespace}}Http\Request\{{name}};
use Zngue\User\Helper\ApiFromRequest;
class AddRequest extends ApiFromRequest
{

    public function rules()
    {
        return [{{rules}}];
    }
    public function messages()
    {
        return [{{message}}];
    }
}
