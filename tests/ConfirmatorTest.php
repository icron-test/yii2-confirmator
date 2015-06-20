<?php
namespace tests;

use icron\confirmator\Confirmator;
use Yii;

class ConfirmatorTest extends  \PHPUnit_Framework_TestCase
{
    public function testGenerateCode()
    {
        $confirmator = $this->getConfirmator();
        $code = $confirmator->generateCode(10);
        $this->assertEquals(10, strlen($code));
    }

    public function testSend()
    {
        $confirmator = $this->getConfirmator();

        $destination1 = '7929758409';
        $confirmator->send($destination1);
        $confirmator->send($destination1);
        $data1 = $confirmator->getDestinationData($destination1);
        $this->assertEquals(2, $data1['count_send']);

        $destination2 = '7929758410';
        $confirmator->send($destination2);
        $data2 = $confirmator->getDestinationData($destination2);
        $this->assertEquals(1, $data2['count_send']);
        $this->assertEquals(2, $data1['count_send']);
    }

    public function testConfirm()
    {
        $confirmator = $this->getConfirmator();
        $destination = '7929758409';
        $confirmator->send($destination);
        $confirmator->send($destination);
        $codes = $confirmator->getCodes($destination);
        foreach ($codes as $code) {
            $this->assertTrue($confirmator->checkCode($destination, $code));
        }
        $this->assertFalse($confirmator->checkCode($destination, 'xxx'));

        $this->assertEquals(Confirmator::STATUS_PENDING, $confirmator->getStatus($destination));
        $this->assertTrue($confirmator->confirm($destination, reset($codes)));
        $this->assertEquals(Confirmator::STATUS_CONFIRMED, $confirmator->getStatus($destination));
        $this->assertFalse($confirmator->confirm($destination, 'xxx'));
    }

    /**
     * @return Confirmator
     * @throws \yii\base\InvalidConfigException
     */
    protected function getConfirmator()
    {
        $stub = $this->getMockBuilder('\icron\confirmator\Confirmator')
            ->setConstructorArgs([['provider' => '\tests\TestProvider']])
            ->setMethods(['getSession'])
            ->getMock();
        $stub->expects($this->any())->method('getSession')->will($this->returnValue(Yii::$app->get('session')));
        return $stub;
    }

    /**
     * Invokes object method, even if it is private or protected.
     * @param object $object object.
     * @param string $method method name.
     * @param array $args method arguments
     * @return mixed method result
     */
    protected function invoke($object, $method, array $args = [])
    {
        $classReflection = new \ReflectionClass(get_class($object));
        $methodReflection = $classReflection->getMethod($method);
        $methodReflection->setAccessible(true);
        $result = $methodReflection->invokeArgs($object, $args);
        $methodReflection->setAccessible(false);
        return $result;
    }
}
 