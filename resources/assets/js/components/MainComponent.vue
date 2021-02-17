<template>
  <div class="tennis_rank">
    <!--Table Head-->
    <div class="flex justify-between align-center table_head">
      <h1>Tennis Ranking</h1>
      <el-button type="primary" @click="insertRow">New Item</el-button>
    </div>
    <el-table
        :data="tableData"
        height="650"
        border
        stripe
        @sort-change="sortChange"
        style="width: 100%">
      <el-table-column prop="date" sortable label="Date" width="120"></el-table-column>
      <el-table-column prop="gender" label="Gender"></el-table-column>
      <el-table-column prop="type" label="Type"></el-table-column>
      <el-table-column prop="ranking" sortable="custom" label="Ranking" width="120"></el-table-column>
      <el-table-column prop="player" label="Player" width="180"></el-table-column>
      <el-table-column prop="country" label="Country"></el-table-column>
      <el-table-column prop="age" sortable label="Age"></el-table-column>
      <el-table-column prop="points" sortable label="Points"></el-table-column>
      <el-table-column prop="tournaments" label="Tournaments"></el-table-column>
      <el-table-column
          fixed="right"
          label="Action"
          width="120">
        <template slot-scope="scope">
          <el-button size="small" type="primary" icon="el-icon-edit" circle @click="editRow(scope.row)"></el-button>
          <el-button size="small" type="danger" icon="el-icon-delete" @click="openMsgBox(scope.row)" circle></el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="mt-3">
      <el-pagination
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
          background
          :current-page="paginate.currentPage"
          :page-sizes="paginate.sizes"
          :page-size="paginate.size"
          layout="total, sizes, prev, pager, next, jumper"
          :total="paginate.total">
      </el-pagination>
    </div>

    <!--Modal-->
    <el-dialog :title="dialog.title" :visible.sync="dialog.show">
      <el-form :model="form" label-position="left" label-width="120px">
        <el-form-item label="Date">
          <el-date-picker format="yyyy-MM-dd" value-format="yyyy-MM-dd" type="date" placeholder="Pick date"
                          v-model="form.date"></el-date-picker>
        </el-form-item>
        <el-form-item label="Gender">
          <el-select v-model="form.gender" placeholder="Select gender">
            <el-option label="Male" value="men"></el-option>
            <el-option label="Female" value="woman"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Type">
          <el-input type="text" v-model="form.type" placeholder="Input type"></el-input>
        </el-form-item>
        <el-form-item label="Ranking">
          <el-input type="text" v-model.number="form.ranking" placeholder="Input ranking"></el-input>
        </el-form-item>
        <el-form-item label="Player">
          <el-input type="text" v-model="form.player" placeholder="Input player's name"></el-input>
        </el-form-item>
        <el-form-item label="Country">
          <el-input type="text" v-model="form.country" placeholder="Input country"></el-input>
        </el-form-item>
        <el-form-item label="Age">
          <el-input type="text" v-model.number="form.age" placeholder="Input age"></el-input>
        </el-form-item>
        <el-form-item label="Points">
          <el-input type="text" v-model="form.points" placeholder="Input points"></el-input>
        </el-form-item>
        <el-form-item label="Tournaments">
          <el-input type="text" v-model="form.tournaments" placeholder="Input tournaments"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="closeDialog">Cancel</el-button>
        <el-button type="primary" @click="submitDialogForm" :loading="dialog.loading">Confirm</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tableData: [],
      form: {
        id: 0,
        date: '',
        gender: '',
        type: '',
        ranking: '',
        player: '',
        country: '',
        age: '',
        points: 0,
        tournaments: '',
      },
      paginate: {
        currentPage: 1,
        sizes: [20, 40, 100, 200],
        size: 20,
        total: 100,
      },
      dialog: {
        title: 'create',
        show: false,
        loading: false,
      },
      order: {
        prop: 'id',
        order: 'asc'
      }
    }
  },
  methods: {
    get() {
      this.$http({
        url: '/api/rank/get',
        arg: {
          page: this.paginate.currentPage,
          page_size: this.paginate.size,
          ...this.order,
        }
      }).then(res => {
        this.tableData = res.data.data;
        // update Pagination.
        this.paginate.total = res.data.total;
      }).catch(err => {
        this.$notify({
          title: 'NetWork Error',
          message: err,
        });
      })
    },
    create() {
      return this.CRUD('create', this.form)
    },
    remove(id) {
      return this.CRUD('delete', {id})
    },
    update() {
      return this.CRUD('update', this.form)
    },
    handleCurrentChange(e) {
      this.paginate.currentPage = e;
      this.get()
    },
    handleSizeChange(e) {
      this.paginate.size = e;
      this.get();
    },
    editRow(row) {
      this.dialog.title = 'edit'
      this.openDialog(row)
    },
    insertRow() {
      this.dialog.title = 'create'
      // for (let x in this.form) {
      //   this.form[x] = '';
      // }
      this.openDialog()
    },
    closeDialog() {
      this.dialog.show = false;
      this.form.id = 0;
    },
    openDialog(row = null) {
      this.dialog.show = true;
      if (row) {
        this.form = JSON.parse(JSON.stringify(row))
      }
    },
    sortChange(event) {
      if (event.order) {
        let order = event.order.replace('ending','');
        this.order.prop = event.prop;
        this.order.order = order;

      } else {
        this.order.prop = 'id';
        this.order.order = 'asc';
      }
      this.get();
    },
    async submitDialogForm() {
      this.dialog.loading = true
      if (this.dialog.title === 'edit') {
        await this.update()
        const id = Number(this.form.id);
        const index = this.tableData.findIndex(n => n.id === id);
        let fm = JSON.parse(JSON.stringify(this.form))
        this.$set(this.tableData, index, fm)
        this.dialog.loading = false
        this.closeDialog()
        this.$notify({
          title: 'Notify',
          message: 'success',
          type: 'success'
        });
      } else {
        await this.create()
        this.dialog.loading = false
        this.closeDialog()
        this.$notify({
          title: 'Notify',
          message: 'success',
          type: 'success'
        });
      }
    },
    openMsgBox(row) {
      this.$confirm('This operation will permanently delete the file, do you want to continue?', 'Alert', {
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(async () => {
        await this.remove(row.id);
        const index = this.tableData.findIndex(n => n.id === row.id);
        this.tableData.splice(index, 1);
        this.$message({
          type: 'success',
          message: 'success!'
        });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'canceled'
        });
      });
    },
    CRUD(action, arg) {
      return new Promise((resolve, reject) => {
        this.$http({
          url: `/api/rank/${action}`,
          arg: arg,
        })
            .then(res => resolve(true))
            .catch(err => reject(false))
      })
    },
  },
  mounted() {
    // init list
    this.get();
  }
}
</script>