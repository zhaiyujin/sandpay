<?php
/**
 * Created by PhpStorm.
 * User: zhaiyujin
 * Date: 20-3-19
 * Time: 下午3:33
 */

namespace zhaiyujin\sandpay\Contracts;


use function is_array;
use zhaiyujin\sandpay\PreCreate\Body;
use zhaiyujin\sandpay\PreCreate\Head;

abstract class AbstractCompare
{
    /**
     * Holds the data as a key => value array
     *
     * @var array
     */
    protected $values = [];

    /**
     * The name of the extended class/data type
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param array $options Data as key => value array
     */
    public function __construct(array $options = null)
    {

        if (is_array($options)) {
            foreach ($options as $name => $value) {
                $this->$name = $value;
            }
        }
    }

    /**
     * PreCreateRequest constructor.
     */

   public function fillData($head, $body)
    {
        $h=new Head();
        $b=new Body();
        foreach ($head as $name => $value) {

            $h->$name = $value;
        }
        $this->values['head']=$h;

        foreach ($body as $name => $value) {
            $b->$name=$value;
        }
        $this->values['body']=$b;

        return $this;
    }


    /**
     * __set implementation
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {



        $setValueMethod = "set{$name}";
        if (method_exists($this, $setValueMethod)) {
            $this->$setValueMethod($value);
        }
    }

    /**
     * __get implementation
     * @param $name
     * @return mixed|null
     * @throws \ReflectionException
     */
    public function &__get($name)
    {
        $nullValue = null;

        $key = strtolower($name);

        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        $setterMethodName = "set{$name}";
        $reflectionClass = new \ReflectionClass($this);
        if ($reflectionClass->hasMethod($setterMethodName)) {
            $parameterClass = $reflectionClass->getMethod($setterMethodName)->getParameters()[0]->getClass();

            if (!empty($parameterClass)) {
                $this->$setterMethodName(new $parameterClass->name());
                return $this->values[$key];
            }
        }

        return $nullValue;
    }

    /**
     * __isset implementation
     * @param $name
     * @return bool
     * @throws \ReflectionException
     */
    public function __isset($name)
    {
        return null !== $this->__get($name);
    }

    /**
     * Recursive algorithm to convert complex types to an array
     *
     * @param array $arrayValues
     * @return array
     */
    protected function convertToArray($arrayValues)
    {
        $returnArray = [];

        foreach ($arrayValues as $key => $value) {
            if ($value instanceof self) {
                $returnArray[$key] = $value->toArray();
            } else if (is_array($value)) {
                $returnArray[$key] = $this->convertToArray($value);
            } else {
                $returnArray[$key] = (string) $value;
            }
        }

        return $returnArray;
    }

    /**
     * Returns the complex type as an array
     *
     * @param boolean $renderTopKey
     * @return array
     */
    public function toArray($renderTopKey = false)
    {
        $returnArray = $this->convertToArray($this->values);

        if ($renderTopKey) {
            return array($this->name => $returnArray);
        } else {
            return $returnArray;
        }
    }

    /**
     * PopulateFromStdClass
     * @param \stdClass $stdClass
     * @throws \ReflectionException
     */
    public function populateFromStdClass(\stdClass $stdClass)
    {
        $reflectionClass = new \ReflectionClass($this);

        $setterMethods = array_filter($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC), function ($reflectionMethod) {
            return (preg_match('/^set.*$/', $reflectionMethod->name));
        });

        foreach ($setterMethods as $reflectionMethod) {
            /* @var $reflectionMethod \ReflectionMethod */
            $methodName = $reflectionMethod->name;
            $stdPropertyName = str_replace('set', '', $methodName);
            $parameterValue = null;
            $reflectionParameter = $reflectionMethod->getParameters()[0];

            if ($reflectionParameter->getClass() instanceof \ReflectionClass) {
                //class
                $classPropertyName = $reflectionParameter->getClass()->name;
                $parameterValue = new $classPropertyName;
                if (isset($stdClass->$stdPropertyName)) {
                    $parameterValue->populateFromStdClass($stdClass->$stdPropertyName);
                }
            } elseif ($reflectionParameter->isArray()) {
                //array
                $arrayType = Reflection::getAbstractClassSetterMethodArrayType($reflectionParameter);
                if (Reflection::isClassNameSimpleType($arrayType)) {
                } else {
                    if (isset($stdClass->$stdPropertyName)) {
                        $parameterValue = [];
                        if (is_array($stdClass->$stdPropertyName)) {
                            foreach ($stdClass->$stdPropertyName as $property) {
                                $class = new $arrayType;
                                $parameterValue[] = $class;
                                $class->populateFromStdClass($property);
                            }
                        } else {
                            $class = new $arrayType;
                            $parameterValue[] = $class;
                            $class->populateFromStdClass($stdClass->$stdPropertyName);
                        }
                    }
                }
            } else {
                //is scalar type
                if (isset($stdClass->$stdPropertyName)) {
                    $parameterValue = $stdClass->$stdPropertyName;
                }
            }

            if (isset($parameterValue, $stdClass->$stdPropertyName)) {
                $this->$methodName($parameterValue);
            }
        }
    }

}