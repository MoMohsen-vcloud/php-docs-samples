<?php
/**
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Google\Cloud\Samples\Functions\SlackSlashCommand\Test;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Cloud Function.
 */
class UnitTest extends TestCase
{
    use TestCasesTrait;

    private static $name = 'receiveRequest';

    public static function setUpBeforeClass(): void
    {
        require_once __DIR__ . '/../index.php';
    }

    /**
      * @dataProvider cases
      */
    public function testFunction(
        $label,
        $body,
        $method,
        $expected,
        $statusCode,
        $headers
    ): void {
        $request = new ServerRequest($method, '/', $headers, $body);
        $response = $this->runFunction(self::$name, [$request]);

        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
            $label . ": status code"
        );

        if ($expected !== null) {
            $output = (string) $response->getBody();
            $this->assertContains($expected, $output, $label . ": contains");
        }
    }

    private static function runFunction($functionName, array $params = []): ResponseInterface
    {
        return call_user_func_array($functionName, $params);
    }
}
