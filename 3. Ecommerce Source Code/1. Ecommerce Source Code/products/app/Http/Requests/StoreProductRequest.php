<?php

namespace App\Http\Requests;

class StoreProductRequest extends ApiFormRequest
{
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function rules()
    {
        return [
            'categoryId' => 'required|exists:categories,id',
            'name' => 'required|string|unique:products,name',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ];
    }
}
