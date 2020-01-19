<?php

namespace Armincms\Wizard;


use Laravel\Nova\Panel; 

class Step extends Panel
{     
    public static function make(string $name, $fields = [])
    {
        return tap(new static($name, $fields), function($step) use ($name) {
            $step->withStep($name);
        });
    } 

    protected function withStep(string $step)
    {   
        return $this->withMeta([
            'step' => $step
        ]);
    } 
}
