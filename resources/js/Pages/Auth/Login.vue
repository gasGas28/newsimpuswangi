<template>
  <div
    class="login-page d-flex justify-content-center align-items-center position-relative overflow-hidden"
  >
    <!-- Background images -->
    <div class="bg-logo bg-logo-left"></div>
    <div class="bg-logo bg-logo-right"></div>

    <!-- Login Box -->
    <div class="login-box p-4 shadow">
      <div class="text-center mb-4">
        <div class="brand-icon bg-primary rounded-circle p-2 d-inline-block mb-2">
          <i class="bi bi-app-indicator text-white fs-4"></i>
        </div>
        <h5 class="fw-bold mt-2 text-gradient">SIMPUSWANGI</h5>
      </div>

      <form>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" placeholder="Enter your email" />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <input
              type="password"
              class="form-control"
              id="password"
              placeholder="Enter your password"
            />
            <span class="input-group-text"><i class="bi bi-eye-slash"></i></span>
          </div>
          <div class="d-flex justify-content-between mt-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe" />
              <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <a href="#" class="text-primary text-decoration-none">Forgot password?</a>
          </div>
        </div>

        <div class="d-grid my-3">
          <button type="submit" class="btn btn-primary rounded-pill">Sign in</button>
        </div>

        <div class="text-center mb-3">
          <span class="text-muted">or</span>
        </div>

        <div class="d-grid mb-3">
          <button
            class="btn btn-light border rounded-pill d-flex align-items-center justify-content-center"
          >
            <img
              src="https://www.svgrepo.com/show/475656/google-color.svg"
              alt="Google"
              width="20"
              class="me-2"
            />
            Sign in with Google
          </button>
        </div>

        <p class="text-center text-muted small">
          Don't have an account? <a href="#" class="text-primary text-decoration-none">Sign up</a>
        </p>
      </form>
    </div>
  </div>
</template>

<style scoped>
  .login-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #4e73df, #8f94fb);
    padding: 2rem;
    position: relative;
  }

  .login-box {
    background: #fff;
    border-radius: 1rem;
    max-width: 400px;
    width: 100%;
    z-index: 10;
  }

  .text-gradient {
    background: linear-gradient(90deg, #0d6efd, #00b4ff);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
  }
</style>
