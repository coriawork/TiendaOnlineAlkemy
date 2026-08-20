<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PrecioValido;
class ProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'precio' => ['required','numeric','min:0',new PrecioValido()],
            'stock' => 'required|integer|min:0',
        ];
    }
}
