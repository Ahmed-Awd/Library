<?php

namespace App\Services;

class DependencyBinder
{
    private string $abstract;
    private string $concrete;
    private string $fallbackConcrete;

    public function __construct(string $abstract)
    {
        $this->abstract = $abstract;
    }

    public static function abstract(string $abstract)
    {
        return new self($abstract);
    }

    public function any(string $concrete)
    {
        $this->fallbackConcrete = $concrete;
        return $this;
    }

    public function local(string $concrete)
    {
        if (config('app.env') == 'local') {
            $this->concrete = $concrete;
        }
        return $this;
    }

    public function testing(string $concrete)
    {
        if (config('app.env') == 'testing') {
            $this->concrete = $concrete;
        }
        return $this;
    }

    public function staging(string $concrete)
    {
        if (config('app.env') == 'staging') {
            $this->concrete = $concrete;
        }
        return $this;
    }

    public function production(string $concrete)
    {
        if (config('app.env') == 'production') {
            $this->concrete = $concrete;
        }
        return $this;
    }

    public function bind()
    {
        app()->bind($this->abstract, $this->concrete ?? $this->fallbackConcrete);
    }
}
