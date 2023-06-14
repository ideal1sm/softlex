<?php
require_once '../vendor/autoload.php';

interface ObjectHandler {
    public function handle(SomeObject $object);
}

class ObjectHandlerFirst implements ObjectHandler {
    public function handle(SomeObject $object)
    {
        return 'handle_object_1';
    }
}

class ObjectHandlerSecond implements ObjectHandler {
    public function handle(SomeObject $object)
    {
        return 'handle_object_2';
    }
}

class SomeObject {
    protected $name;

    public function __construct(string $name) { }

    public function getObjectName() { }
}
class SomeObjectsHandler {
    protected $handlers;
    public function __construct() { }

    public function addHandler(string $objectName, ObjectHandler $handler)
    {
        $this->handlers[$objectName] = $handler;
    }

    public function handleObjects(array $objects): array {
        $handlers = [];
        foreach ($objects as $object) {
            $objectName = $object->getObjectName();
            if (isset($this->handlers[$objectName])) {
                $handler = $this->handlers[$objectName];
                $handler->handle($object);
            }
        }

        return $handlers;
    }
}

$objects = [
    new SomeObject('object_1'),
    new SomeObject('object_2')
];

$soh = new SomeObjectsHandler();
$soh->addHandler('object_1', new ObjectHandlerFirst());
$soh->addHandler('object_2', new ObjectHandlerSecond());
$soh->handleObjects($objects);