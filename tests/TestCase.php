<?php
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
        
        
    }

    protected function apiAs($user, $method, $uri, array $data = [], array $headers = [])
    {
        $user = $user ? $user : factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $headers = array_merge(
            ['Authorization' => 'Bearer ' . $token],
            $headers
        );

        return $this->json($method, $uri, $data, $headers);
    }

    protected function api($method, $uri, array $data = [], array $headers = [])
    {
        return $this->json($method, $uri, $data, $headers);
    }
}
