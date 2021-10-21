<template>
  <div class="row">

      <div class="col-lg-4">
          <strong>Tareas :</strong>
          <select v-model="task_model">
              <option value=""></option>
              <option value="1">TODAS</option>
              <option value="2">MIAS</option>
          </select>
          <!-- <select2 :options="[{id:1,text:'TODAS'},{id:2,text:'MIAS'}]" v-model="task_model" class="select-add-supplies"> -->
        <!-- </select2>           -->
      </div>
<div class="col-lg-4">
          <strong>Estado :</strong>
          <select v-model="status_model">
              <option value=""></option>
              <option value="0">TODAS</option>
              <option value="1">Activas</option>
          </select>
          <!-- <select2 :options="[{id:1,text:'TODAS'},{id:2,text:'Activas'}]" v-model="status_model" class="select-add-supplies"> -->
        <!-- </select2>                     -->
</div>
      <div class="col-lg-2">
        <button class="btn btn-success btn-xs" @click="search"><i class="mdi mdi-account-search fz"></i></button>          
      </div>


      <div class="col-lg-1 offset-11">
            <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#mdl_created_task"><i class="mdi mdi-account-multiple-plus fz"></i></button>
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
              <th>Descripcion</th>
              <th>Fecha fin</th>              
              <th>Estado</th>
              <th>Creado por:</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
              <tr v-for="(v,i) in task" :key="i">                
                  <th>{{ v.id }}</th>
                  <td>
                      <div v-if="!v.editable">
                        {{ v.description }}
                      </div>
                      <div v-else>
                          <input type="text" class="form-control" :value="v.description">  
                      </div>
                      
                      
                      </td>
                  <td>{{ v.date_finish }}</td>
                  <td>{{ v.estado }}</td>                                    
                  <td>{{ v.created_by }}</td>
                <td>
                  <div v-if="v.editable">
                        
                        <button class="btn btn-success btn-xs" @click="btn_edit_user(v,$event)" ><i class="mdi mdi-content-save-settings fz"></i></button>
                        <button class="btn btn-warning btn-xs" @click="btn_edit(v,false)"><i class="mdi mdi-content-duplicate fz"></i></button>                      
                  </div>
                  <div v-else>                        
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
<div class="modal fade" id="mdl_created_task" tabindex="-1" role="dialog" aria-labelledby="mdl_created_task" aria-hidden="true">
  <div class="modal-dialog modal-xs" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >CREACI&Oacute;N TAREAS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <label>Descripcion :  <span class="text-danger">*</span> </label>                
                <input type="text" class="form-control" v-model="description">
            </div>
            <div class="col-lg-6">
                <label>Fecha Fin :  <span class="text-danger">*</span></label>                
                <input type="date"  class="form-control" v-model="date_end">
            </div>            
        </div>
      </div>  
      <div class="modal-footer">
        <button type="button" class="btn btn-success" @click="saveTask">Guardar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>    
    </div>
  </div>
  </div>

  </div>
</template>
<script>
//import Select2 from "v-select2-component";
export default { 
  //components: { Select2 },
  /*components: {
    "vue-select": require("vue-select")
  },*/
  title: "Gocho | task",
  data: () => ({
    task: [],    
    all:1,
    status:1,
    status_model:0,
    task_model:1,
    description:'',
    date_end:''
  }),
  created(){
    this.getTask();
  },
  methods: {
          
saveTask(){
    let param={description:this.description,date_finish:this.date_end};
        this.$http
                .post("/task/crear",param)
                .then((res) => {
                if(res.status==201){
                    sweetMessage(
                    "\u00A1Registro exitoso!",
                    "\u00A1 Se ha realizado con \u00E9xito su solicitud!"
                    );
                    this.task.push(res.data.task);
                    $('#mdl_created_task').modal('hide');

                }else if (res.status==400){ 
                    sweetMessage("ERROR!","El usuario ya se encuentra registrado", "error");
                }
                
                }).catch(
    function (error) {      
       sweetMessage("ERROR!","El usuario ya se encuentra registrado", "error");
    }
  );

},
search(){
    this.$http
        .get("/task/listData/"+this.task_model+"/"+this.status_model)
        .then((res) => {
          let d = res.data;          
          this.task = d;
        });
},
     getTask() {
       this.$http
        .get("/task/listData/"+this.task_model+"/"+this.status_model)
        .then((res) => {
          let d = res.data;          
          this.task = d;
        });
    },
    btn_edit_user(v,event){ 
        sweetMessageTimeOut(
            "Procesando ...",
            "\u00A1  Su solicitud  se encuentra en ejecuci\u00F3n ! ",
            3000
      );       
        var name=event.target.parentNode.parentNode.parentNode.parentNode.children[1].firstElementChild.firstElementChild.value;
        console.log(name);
        this.$http
        .post("/task/update",{description:name,id:v.id})
        .then((res) => {
          if(res.status==201){
           v.description=name;
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
        .get("/task/delete/"+id)
        .then((res) => {                    
          if(res.status==201){
            this.task.splice(i,1);
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
