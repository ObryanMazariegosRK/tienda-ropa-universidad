<?php

namespace App\Http\Requests;
use App\Domain\Enum\ProductSaleType;
use App\Domain\Enum\ProductStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
//Para las reglas de validación los que usaremos con los Enums
use Illuminate\Validation\Rule;

//al heredar FormRequest podemos interceptar peticiones, validar datos
//y lanzar errores
class StoreProductRequest extends FormRequest
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
            'categoryId'  => ['required', 'integer', 'exists:categories,id'],
            'name'        => ['required', 'string', 'min:3', 'max:100', 'unique:products,name'],
            'description' => ['required', 'string', 'max:2000'],
            'price'       => ['required', 'numeric', 'gt:0'],
            'offerPrice'  => ['nullable', 'numeric', 'gt:0', 'lt:price'],
            'saleType'    => ['required', 'string', Rule::enum(ProductSaleType::class)],
            'status'      => ['required', 'string', Rule::enum(ProductStatus::class)],
            
            

            
            //'images' representa el contenedor (debe ser un arreglo, pero es opcional)
            'images'   => ['nullable', 'array'],
            
            //'images.*' valida cada archivo individual dentro del arreglo
            'images.*' => [
                'image',             // Debe ser una imagen
                'mimes:jpeg,png,jpg,webp', // Formatos permitidos
                'max:2048'           // Tamaño máximo en KB (2MB por imagen)
            ],
        ];
    }

    /**
     * Mensajes de error personalizados (Opcional, pero muy recomendado)
     */
    public function messages(): array
    {
        return [
            'categoryId.exists' => 'La categoría seleccionada no existe en la base de datos.',
            'offerPrice.lt'     => 'El precio de oferta debe ser estrictamente menor que el precio regular.',
            'saleType.Illuminate\Validation\Rules\Enum' => 'El tipo de venta no es válido.',
            'status.Illuminate\Validation\Rules\Enum'=>'El estado del producto no es válido.',
            'images.array'    => 'El formato de las imágenes no es válido.',
            'images.*.image'  => 'Uno de los archivos subidos no es una imagen.',
            'images.*.mimes'  => 'Las imágenes deben ser de tipo: jpeg, png, jpg o webp.',
            'images.*.max'    => 'Cada imagen no debe pesar más de 2MB.',
            'name.unique' => 'Ya existe un producto con este nombre. Por favor, elige otro para evitar duplicados en la URL.'
        ];
    }
}
