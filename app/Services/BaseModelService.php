<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseModelService
{
    abstract public function model(): string;

    public function first()
    {
        return $this->model()::first();
    }

    public function all()
    {
        return $this->model()::all();
    }

    public function find($primaryKey)
    {
        return $this->model()::find($primaryKey);
    }

    public function findOrFail($primaryKey)
    {
        return $this->model()::findOrFail($primaryKey);
    }

    public function delete($primaryKey)
    {
        return $this->model()::destroy($primaryKey);
    }

    public function create(array $validatedData)
    {
        $validatedData['created_by'] = auth()->id ?? 1;
        $validatedData['updated_by'] = auth()->id ?? 1;
        return $this->model()::create($validatedData);
    }

    public function update(Model $model, array $data): bool
    {
        $data['updated_by'] = auth()->id ?? 1;
        return $model->update($data);
    }

    public static function generateSlug($name)
    {
        // Generate a slug using Laravel's Str::slug helper
        return Str::slug($name, '-');
    }
}
