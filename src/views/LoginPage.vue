<template>
  <div class="login-container">
    <h1 class="login-title">Login Akun</h1>

    <div v-if="message" class="message" :class="{ error: isError }">
      {{ message }}
    </div>

    <form @submit.prevent="handleLogin" class="login-form">
      <input
        v-model="form.email"
        type="email"
        placeholder="Email"
        class="input"
        :disabled="loading"
      />
      <input
        v-model="form.password"
        type="password"
        placeholder="Password"
        class="input"
        :disabled="loading"
      />

      <button type="submit" class="btn-primary" :disabled="loading">
        {{ loading ? "Memproses..." : "Login" }}
      </button>
    </form>
  </div>
</template>

<script>
import { loginUser } from "../api"; // pastikan fungsi loginUser sudah dibuat di file api.js

export default {
  name: "LoginForm",
  data() {
    return {
      form: { email: "", password: "" },
      loading: false,
      message: "",
      isError: false,
    };
  },
  methods: {
    async handleLogin() {
      if (!this.form.email || !this.form.password) {
        this.message = "Email dan Password wajib diisi!";
        this.isError = true;
        return;
      }

      this.loading = true;
      this.message = "";
      this.isError = false;

      try {
        const response = await loginUser(this.form);
        if (response && response.token) {
          localStorage.setItem("token", response.token);
          this.message = "Login berhasil! Mengalihkan...";
          this.isError = false;

          // arahkan ke halaman utama atau dashboard
          setTimeout(() => {
          window.location.replace("/tabs/tab2");
        }, 800);

        } else {
          this.message = "Email atau password salah!";
          this.isError = true;
        }
      } catch (error) {
        this.message = "Gagal login: " + error.message;
        this.isError = true;
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 100px auto;
  padding: 30px;
  border-radius: 10px;
  background: #f9f9f9;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  font-family: Arial, sans-serif;
}
.login-title {
  text-align: center;
  color: #333;
  margin-bottom: 25px;
}
.message {
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
  border: 1px solid #c3e6cb;
  background: #d4edda;
  color: #155724;
}
.message.error {
  background: #f8d7da;
  color: #721c24;
  border-color: #f5c6cb;
}
.login-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}
.input {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.btn-primary {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
}
.btn-primary:disabled {
  background: #6c757d;
  cursor: not-allowed;
}
</style>
