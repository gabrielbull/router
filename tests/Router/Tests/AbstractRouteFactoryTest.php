<?php
namespace Router\Tests;

class AbstractRouteFactoryTest extends AbstractKleinTest
{

    protected function getDefaultMethodsToMock()
    {
        return array(
            'build',
        );
    }

    protected function getMockForFactory()
    {
        return $this->getMockForAbstractClass('\Router\AbstractRouteFactory');
    }

    protected function getMockBuilderForFactory(array $methods_to_mock = null)
    {
        $methods_to_mock = $methods_to_mock ?: $this->getDefaultMethodsToMock();

        return $this->getMockBuilder('\Router\AbstractRouteFactory')
            ->setMethods($methods_to_mock);
    }


    public function testNamespaceGetSet()
    {
        // Test data
        $test_namespace = '/users';

        // Empty constructor
        $factory = $this->getMockForFactory();

        $this->assertNull($factory->getNamespace());

        // Set in constructor
        $factory = $this->getMockBuilderForFactory()
            ->setConstructorArgs(
                array(
                    $test_namespace,
                )
            )
            ->getMock();

        $this->assertSame($test_namespace, $factory->getNamespace());

        // Set in method
        $factory = $this->getMockForFactory();
        $factory->setNamespace($test_namespace);

        $this->assertSame($test_namespace, $factory->getNamespace());
    }

    public function testAppendNamespace()
    {
        // Test data
        $test_namespace = '/users';
        $test_namespace_append = '/names';

        $factory = $this->getMockForFactory();
        $factory->setNamespace($test_namespace);
        $factory->appendNamespace($test_namespace_append);

        $this->assertSame(
            $test_namespace . $test_namespace_append,
            $factory->getNamespace()
        );
    }
}
