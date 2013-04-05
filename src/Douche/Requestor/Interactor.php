<?php

namespace Douche\Requestor;

interface Interactor 
{
    public function __invoke(Request $request);
}
