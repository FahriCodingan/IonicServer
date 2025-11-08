<template>
  <div class="container">
    <h1 class="title">Data Akun User</h1>

    <div v-if="message" class="message">{{ message }}</div>

    <form @submit.prevent="handleSubmit" class="form">
      <input
        v-model="form.nama"
        type="text"
        placeholder="Nama"
        class="input"
        :disabled="loading"
      />
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
        {{ loading ? "Loading..." : editId ? "Update" : "Tambah" }}
      </button>
      <button
        v-if="editId"
        type="button"
        @click="handleCancel"
        class="btn-cancel"
        :disabled="loading"
      >
        Batal
      </button>
    </form>

    <div v-if="loading && !editId" class="loading">Loading data...</div>
    <table v-else class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Password</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="users.length === 0">
          <td colspan="4" align="center">Tidak ada data</td>
        </tr>
        <tr v-for="u in users" :key="u.id">
          <td>{{ u.id }}</td>
          <td>{{ u.nama }}</td>
          <td>{{ u.email }}</td>
          <td>{{ u.password }}</td>
          <td>
            <button @click="handleEdit(u)" class="btn-edit" :disabled="loading">
              Edit
            </button>
            <button
              @click="handleDelete(u.id)"
              class="btn-delete"
              :disabled="loading"
            >
              Hapus
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { getUsers, addUser, updateUser, deleteUser } from "../api";

export default {
  name: "UserTable",
  data() {
    return {
      users: [],
      form: { nama: "", email: "", password: "" },
      editId: null,
      loading: false,
      message: "",
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      this.loading = true;
      try {
        this.users = await getUsers();
        this.message = "";
      } catch (error) {
        this.message = "Error loading data: " + error.message;
      } finally {
        this.loading = false;
      }
    },
    async handleSubmit() {
      if (!this.form.nama || !this.form.email || !this.form.password) {
        this.message = "Nama, Email dan Password wajib diisi";
        return;
      }
      this.loading = true;
      try {
        if (this.editId) {
          await updateUser(this.editId, this.form);
          this.message = "Data berhasil diupdate!";
          this.editId = null;
        } else {
          await addUser(this.form);
          this.message = "Data berhasil ditambahkan!";
        }
        this.form = { name: "", email: "", password: "" };
        this.fetchUsers();

        setTimeout(() => {
          window.location.reload();
        }, 3000);
      } catch (error) {
        this.message = "Error: " + error.message;
      } finally {
        this.loading = false;
      }
    },
    async handleDelete(id) {
      if (confirm("Yakin hapus data ini?")) {
        this.loading = true;
        try {
          await deleteUser(id);
          this.message = "Data berhasil dihapus!";
          this.fetchUsers();

           setTimeout(() => {
        window.location.reload();
      }, 1000);
        } catch (error) {
          this.message = "Error: " + error.message;
        } finally {
          this.loading = false;
        }
      }
    },
    handleEdit(user) {
      this.form = { name: user.name, email: user.email, password: user.password };
      this.editId = user.id;
      this.message = "";
    },
    handleCancel() {
      this.form = { name: "", email: "", password: "" };
      this.editId = null;
      this.message = "";
    },
  },
};
</script>

<style scoped>
.container {
  padding: 30px;
  font-family: Arial, sans-serif;
  max-width: 1000px;
  margin: 0 auto;
}
.title {
  text-align: center;
  color: #333;
  margin-bottom: 30px;
}
.message {
  padding: 10px;
  margin-bottom: 20px;
  border-radius: 5px;
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}
.form {
  margin-bottom: 30px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}
.input {
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  flex: 1;
  min-width: 200px;
}
.btn-primary,
.btn-cancel,
.btn-edit,
.btn-delete {
  border: none;
  padding: 8px 15px;
  border-radius: 5px;
  cursor: pointer;
}
.btn-primary {
  background: #007bff;
  color: #fff;
}
.btn-cancel {
  background: #6c757d;
  color: #fff;
}
.btn-edit {
  background: #ffc107;
  color: #212529;
  margin-right: 8px;
}
.btn-delete {
  background: #dc3545;
  color: #fff;
}
.table {
  width: 100%;
  border-collapse: collapse;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
th,
td {
  padding: 12px;
  border-bottom: 1px solid #dee2e6;
}
th {
  background: green;
  text-align: left;
  font-weight: bold;
}
.loading {
  text-align: center;
  padding: 20px;
  font-size: 18px;
  color: #6c757d;
}
</style>