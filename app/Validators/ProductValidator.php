<?php

namespace App\Validators;

trait ProductValidator
{
    public function rules(\Validator $v)
    {
        return $v->bag([
            $v->bag([
                $v->required(),
                $v->string(),
            ], true)->applyTo('name'),

            $v->bag([
                $v->required()->setMessage('You have to provide a :field'),
                $v->range(['>=' => 0], ['<=' => 1000]),
            ])->applyTo(['product', 'price']),

            $v->number()->setMessage('The :field must be a number')->applyTo('rating'),
        ]);
    }
}
