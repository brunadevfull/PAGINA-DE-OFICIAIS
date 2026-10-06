<?php

namespace App\Http\Requests;

trait AcceptsCamelCaseKeys
{
    protected function mergeCamelCaseKeys(array $map): void
    {
        $merge = [];
        foreach ($map as $camel => $snake) {
            if ($this->has($camel) && !$this->has($snake)) {
                $merge[$snake] = $this->input($camel);
            }
        }
        if ($merge) {
            $this->merge($merge);
        }
    }
}
