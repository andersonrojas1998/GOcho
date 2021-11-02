<template>
  <div class="row">
      <div class="col-lg-1 offset-11">
            <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#mdl_created_user"><i class="mdi mdi-account-multiple-plus fz"></i></button>
      </div>
      
    <div class="col-lg-12">
      <div class="table-responsive">
        <table
          id=""
          class="table table-striped table-bordered"
          style="width: 100%"
        >
          <thead>
            <tr class="bg-secondary">
              <th>#</th>
              <th>Identificacion</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Estado</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="(v,i) in users" :key="i">                
                  <th>{{ v.id }}</th>
                  <td>{{ v.identificacion }}</td>
                  <td>
                      <div v-if="!v.editable">
                        {{ v.name }}
                      </div>
                      <div v-else>
                        <input type="text" class="form-control" :value="v.name">
                      </div>
                      
                      
                      </td>
                  <td>{{ v.email }}</td>
                  <th><div v-if="v.estado==1">
                      <p class="text-success">Activo</p>
                  </div>
                  <div v-else>
                      <p class="text-danger">Inactivo</p>
                  </div>
                </th>
                <td>
                  
                  <div v-if="v.editable">
                        
                        <button class="btn btn-success btn-xs" @click="btn_edit_user(v,$event)" ><i class="mdi mdi-content-save-settings fz"></i></button>
                        <button class="btn btn-warning btn-xs" @click="btn_edit(v,false)"><i class="mdi mdi-content-duplicate fz"></i></button>                      
                  </div>
                  <div v-else>
                        <button class="btn btn-primary btn-xs" @click="showUser(v)" data-toggle="modal" data-target="#mdl_showinfo"><i class="mdi mdi-account-search-outline fz"></i></button>
                        <button class="btn btn-success btn-xs" @click="btn_edit(v,true)"><i class="mdi mdi-account-settings fz"></i></button>
                        <button class="btn btn-danger btn-xs" @click="btn_delete(v.id,i)"><i class="mdi mdi-delete-forever fz"></i></button>
                  </div>
                    
                </td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
<!--  -->
<div class="modal fade" id="mdl_showinfo" tabindex="-1" role="dialog" aria-labelledby="mdl_showinfo" aria-hidden="true">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-center d-block" >INFO PERSONAL</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Identificacion</th>
                            <th>Nombre</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>{{ objUser.id }}</td>
                            <td>{{ objUser.identificacion }}</td>
                            <td>{{ objUser.name }}</td>
                            <td>{{ objUser.email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
      </div>      
    </div>
  </div>
  </div>


<!--  -->
<div class="modal fade" id="mdl_created_user" tabindex="-1" role="dialog" aria-labelledby="mdl_created_user" aria-hidden="true">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h5 class="modal-title" >CREACI&Oacute;N PERSONAL</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-4">
                <label>Identificaci&oacute;n :  <span class="text-danger">*</span> </label>                
                <input type="number" class="form-control" v-model="dni">
            </div>
            <div class="col-lg-4">
                <label>Nombre :  <span class="text-danger">*</span></label>                
                <input type="text" maxlength="100" class="form-control" v-model="name">
            </div>
            <div class="col-lg-4">
                <label>Email : <span class="text-danger">*</span></label>                
                <input type="email" class="form-control" v-model="email">
            </div>
        </div>
      </div>  
      <div class="modal-footer">
        <button type="button" class="btn btn-success" @click="saveUser">Guardar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>    
    </div>
  </div>
  </div>

  </div>
</template>
<script>
export default { 
  title: "Gocho | Usuarios",
  data: () => ({
    users: []  ,
    objUser:{},
    dni:'',    
    name:'',
    email:''
  }),
  created(){
    this.getUsers();
  },
  methods: {
      showUser(v){
          this.objUser=v;
      },
saveUser(){
    let param={dni:this.dni,name:this.name,email:this.email};
        this.$http
                .post("/users/create",param)
                .then((res) => {
                if(res.status==201){
                    sweetMessage(
                    "\u00A1Registro exitoso!",
                    "\u00A1 Se ha realizado con \u00E9xito su solicitud!"
                    );
                    this.users.push(res.data.user);
                    $('#mdl_created_user').modal('hide');

                }else if (res.status==400){ 
                    sweetMessage("ERROR!","El usuario ya se encuentra registrado", "error");
                }
                
                }).catch(
    function (error) {      
       sweetMessage("ERROR!","El usuario ya se encuentra registrado", "error");
    }
  );

},
     getUsers() {
       this.$http
        .get("/api/v1/user/listData")
        .then((res) => {
          let d = res.data;
          console.log(res);
          this.users = d;
        });
    },
    btn_edit_user(v,event){ 
        sweetMessageTimeOut(
            "Procesando ...",
            "\u00A1  Su solicitud  se encuentra en ejecuci\u00F3n ! ",
            3000
      );       
        var name=event.target.parentNode.parentNode.parentNode.parentNode.children[2].firstElementChild.firstElementChild.value;
        
        this.$http
        .post("/users/update",{name:name,id:v.id})
        .then((res) => {
          if(res.status==201){
           v.name=name;
           v.editable=false;
          }
          
        });

    },
    btn_edit(v,state){
        v.editable=state;
    },
    btn_delete(id,i){
        sweetMessageTimeOut(
            "Procesando ...",
            "\u00A1  Su solicitud  se encuentra en ejecuci\u00F3n ! ",
            5000
      );
      this.$http
        .get("/users/delete/"+id)
        .then((res) => {                    
          if(res.status==201){
            this.users.splice(i,1);
          }
          
        });

    }
    /*btn_saveLoad() {
      this.display = 1;
      
      const formData = new FormData($("#frm_load")[0]);
      this.$http
        .post("/Paciente/admision/createExcelPatient", formData)
        .then((result) => {
          let data = result.data.length;
          if (data > 0) {
            this.resumen = result.data;
            this.dt_patientExcel();
            this.display = 0;
            sweetMessage(
              "\u00A1Registro exitoso!",
              "\u00A1 Se ha realizado con \u00E9xito su solicitud!"
            );
          }
        })
        .catch(function (error) {
          if (typeof error.response.data.error != "undefined") {
            sweetMessage(
              "ERROR!",
              JSON.parse(error.response.data.error).message,
              "error"
            );
          } else {
            sweetMessage("ERROR!", error.response.data.message, "error");
          }
        });
    },*/
  },
};
</script>
<style scoped>
.fz {
font-size: 22px !important;
}
</style>
