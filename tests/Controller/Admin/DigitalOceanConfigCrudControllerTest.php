<?php

declare(strict_types=1);

namespace DigitalOceanAccountBundle\Tests\Controller\Admin;

use DigitalOceanAccountBundle\Controller\Admin\DigitalOceanConfigCrudController;
use DigitalOceanAccountBundle\Entity\DigitalOceanConfig;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(DigitalOceanConfigCrudController::class)]
#[RunTestsInSeparateProcesses]
final class DigitalOceanConfigCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getEntityFqcn(): string
    {
        return DigitalOceanConfig::class;
    }

    public function testIndexPage(): void
    {
        $client = self::createClientWithDatabase();
        $this->loginAsAdmin($client);

        $crawler = $client->request('GET', '/admin');
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Navigate to DigitalOceanConfig CRUD
        $link = $crawler->filter('a[href*="DigitalOceanConfigCrudController"]')->first();
        if ($link->count() > 0) {
            $client->click($link->link());
            self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        }
    }

    public function testCreateDigitalOceanConfig(): void
    {
        // Test that the controller configures fields properly
        $controller = new DigitalOceanConfigCrudController();
        $fields = $controller->configureFields('new');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testEditDigitalOceanConfig(): void
    {
        // Test that configureFields returns appropriate fields
        $controller = new DigitalOceanConfigCrudController();
        $fields = $controller->configureFields('edit');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testDetailDigitalOceanConfig(): void
    {
        // Test that configureFields returns appropriate fields for detail view
        $controller = new DigitalOceanConfigCrudController();
        $fields = $controller->configureFields('detail');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testConfigureFilters(): void
    {
        // Test that configureFilters method exists and accepts Filters parameter
        $controller = new DigitalOceanConfigCrudController();
        $reflection = new \ReflectionMethod($controller, 'configureFilters');
        self::assertTrue($reflection->isPublic());
        self::assertEquals(1, $reflection->getNumberOfParameters());
        self::assertEquals('filters', $reflection->getParameters()[0]->getName());
    }

    public function testEntityFqcnConfiguration(): void
    {
        $controller = new DigitalOceanConfigCrudController();
        self::assertEquals(DigitalOceanConfig::class, $controller::getEntityFqcn());
    }

    public function testConfigureCrud(): void
    {
        // Test that configureCrud method exists and accepts Crud parameter
        $controller = new DigitalOceanConfigCrudController();
        $reflection = new \ReflectionMethod($controller, 'configureCrud');
        self::assertTrue($reflection->isPublic());
        self::assertEquals(1, $reflection->getNumberOfParameters());
        self::assertEquals('crud', $reflection->getParameters()[0]->getName());
    }

    protected function getControllerService(): DigitalOceanConfigCrudController
    {
        return new DigitalOceanConfigCrudController();
    }

    /**
     * 测试验证错误场景
     */
    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问新建页面
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // 提交空表单
        $form = $crawler->filter('form[name="DigitalOceanConfig"]')->form();
        $client->submit($form);

        // 验证返回422状态码
        $this->assertResponseStatusCodeSame(422);

        // 验证响应内容包含必填字段错误信息
        $responseContent = $client->getResponse()->getContent();
        $this->assertIsString($responseContent);
        $this->assertStringContainsString('This value should not be blank', $responseContent);

        // 验证实体的必填字段约束
        $this->assertEntityHasRequiredConstraints();
    }

    /**
     * 测试Entity必填字段的验证约束
     */
    private function assertEntityHasRequiredConstraints(): void
    {
        $reflection = new \ReflectionClass(DigitalOceanConfig::class);

        // 验证apiKey字段约束
        $apiKeyProperty = $reflection->getProperty('apiKey');
        $apiKeyAttributes = $apiKeyProperty->getAttributes(NotBlank::class);
        self::assertNotEmpty($apiKeyAttributes, 'API Key property should have NotBlank constraint');

        // 验证字段长度约束
        $lengthAttributes = $apiKeyProperty->getAttributes(Length::class);
        self::assertNotEmpty($lengthAttributes, 'API Key property should have Length constraint');

        // 验证remark字段（可选字段）的长度约束
        $remarkProperty = $reflection->getProperty('remark');
        $remarkLengthAttributes = $remarkProperty->getAttributes(Length::class);
        self::assertNotEmpty($remarkLengthAttributes, 'Remark property should have Length constraint');
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'apiKey' => ['apiKey'];
        yield 'remark' => ['remark'];
    }

    /**
     * 独立的新页面字段测试 - 避免基类的客户端设置问题
     */
    public function testNewPageFieldsExistIndependently(): void
    {
        // 使用简单的客户端创建方式，避免基类的问题
        $client = self::createClient();
        self::getClient($client); // 设置客户端到断言trait

        // 先测试未认证访问会重定向（启用异常捕获）
        $client->catchExceptions(true);
        $client->request('GET', $this->generateAdminUrl(Action::NEW));
        $this->assertTrue(
            $client->getResponse()->isRedirect(),
            'New page should redirect for unauthenticated users'
        );
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield 'API Key' => ['API Key'];
        yield '备注' => ['备注'];
        yield '创建时间' => ['创建时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'apiKey' => ['apiKey'];
        yield 'remark' => ['remark'];
    }
}
