<?php
namespace {{namespace}}Http\Request\{{name}};
use Zngue\User\Helper\ApiFromRequest;

class SaveRequest extends ApiFromRequest
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
