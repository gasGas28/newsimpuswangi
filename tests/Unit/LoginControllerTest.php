<?php

namespace Tests\Unit;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_store_mengembalikan_redirect_dashboard_untuk_owner_dan_password_yang_masih_berlaku(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);

        $user = Mockery::mock();
        $user->forgotten_password_time = now()->timestamp;
        $user->created_on = now()->subDays(30)->timestamp;
        $user->shouldReceive('roleNames')->once()->andReturn(['owner']);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['username' => 'owner', 'password' => 'rahasia'])
            ->andReturnTrue();
        Auth::shouldReceive('id')->once()->andReturn(1);
        Auth::shouldReceive('user')->once()->andReturn($user);

        $request = $this->makeRequest();
        $response = (new LoginController())->store($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([
            'ok' => true,
            'redirect' => route('dashboard'),
            'require_password_change' => false,
        ], $response->getData(true));
        $this->assertFalse($request->session()->get('force_password_change'));
    }

    public function test_store_melempar_error_validasi_saat_recaptcha_gagal(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false]),
        ]);

        Auth::shouldReceive('attempt')->never();

        try {
            (new LoginController())->store($this->makeRequest());
            $this->fail('Validasi reCAPTCHA seharusnya gagal.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Verifikasi reCAPTCHA gagal. Silakan coba lagi.'], $exception->errors()['captcha']);
        }
    }

    public function test_store_melempar_error_validasi_saat_kredensial_tidak_valid(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['username' => 'owner', 'password' => 'rahasia'])
            ->andReturnFalse();

        try {
            (new LoginController())->store($this->makeRequest());
            $this->fail('Validasi kredensial seharusnya gagal.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Username atau password salah.'], $exception->errors()['username']);
        }
    }

    private function makeRequest(): Request
    {
        $request = Request::create('/login', 'POST', [
            'username' => 'owner',
            'password' => 'rahasia',
            'g-recaptcha-response' => 'token-captcha',
        ]);
        $request->setLaravelSession($this->app['session']->driver());
        $request->session()->start();

        return $request;
    }
}