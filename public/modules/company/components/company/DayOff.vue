

<template>
  <div>
    <div class="row">
      <div class="col-12" v-loading="loading">
        <div class="row">
          <div class="col-12 " v-if="picked_day.length > 0">
              <div class="d-flex flex-column justify-content-center align-items-center bg-warning p-2">

                <a href="#!" class="pb-2" style="font-weight: 400;padding: 0px 15px;color:white!important;"> {{picked_day.length}} {{trans('companies.title.day selected')}}</a> 
                <button type="button" class="btn btn-info btn-sm" @click="tambahDayOff()"><i class="fa fa-plus"></i> {{trans('companies.title.add dayoff')}}</button>
              </div>
          </div>
          <div class="col-12">
            <el-calendar :key="keyCalendar">
                  <template
                      slot="dateCell"
                      slot-scope="{data}">
                      <div class="el-calendar-day__child" style="background-color:red" v-if="findDayOf(data.day)" @click="addDayOff(data, 'dayon')">
                          <p class="is-selected" style="color:white">
                          {{ data.day.split('-')[2] }} {{ findDayOf(data.day).deskripsi_libur}}
                          </p>
                      </div>
                      <div v-else class="el-calendar-day__child" 
                          :style="picked_day.indexOf(data.day) != -1?'background-color: #c1c6f7;':''" 
                          @click="addDayOff(data, 'dayoff')">
                          <p>
                          {{ data.day.split('-')[2] }}
                          </p>
                      </div>
                  </template>
              </el-calendar>
            </div>
          </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'form-backend-validation';
import axios from 'axios';
import Toast from '../../../../Core/Assets/js/mixins/Toast';

export default {
    mixins: [Toast],
    props: ['company_id'],
    data() {
        return {
            keyCalendar: 1,
            day_off:[],
            picked_day:[],
            loading:false,
        }
    },
    methods: {
        addDayOff(data, type) {
            if(this.isPassedDay(data.day)) {
                return this.Toast({
                    icon: 'info',
                    title: this.trans('companies.messages.passed day')
                })
            }
            if(type === 'dayoff') {              
                for (var key in this.picked_day) {                  
                      if (this.picked_day[key] == data.day) {
                          this.picked_day.splice(key, 1);
                          return false;
                      }
                }

                this.picked_day.push(data.day);
            } else {
                this.$confirm(this.trans('dayoff.destroy'), this.trans('companies.title.dayoff'), {
                    confirmButtonText: this.trans('core.confirm'),
                    cancelButtonText: this.trans('core.button.cancel'),
                    type: 'warning'
                }).then(() => {
                    this.loading = true;
                    const form = new Form({
                        date: data.day
                    });

                    form.post(route('api.company.company.dayon', {company: this.company_id}))
                    .then(response => {
                        this.fetchDayOff();
                        this.Toast({
                            icon: "success",
                            title: response.message
                        });
                    })
                    .catch(err => {
                        console.log(err)
                    })

                }).catch(() => {
                    // this.$message({
                    //     type: 'info',
                    //     message: 'Canceled'
                    // });          
                });
                
            }
        },
        isPassedDay(day) {
            if (new Date(day).getTime() <= new Date().getTime()) {
                return true;
            }
            return false;
        },
        tambahDayOff(){
          let self = this;
          this.$prompt('Please input description', 'Description', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                inputPattern: /[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*/,
                inputErrorMessage: 'Masukkan deskripsi'
            }).then(({ value }) => {
                this.loading = true;
                const form = new Form({
                    date: self.picked_day,
                    description: value
                });
                form.post(route('api.company.company.dayoff', {company: this.company_id}))
                    .then(response => {        
                        this.fetchDayOff();
                        this.picked_day = [];
                        this.Toast({
                            icon: "success",
                            title: response.message
                        });

                    })
                    .catch(err => {
                        console.log(err)
                    })
                
            }).catch((e) => {
                // console.log(e)
                // this.$message({
                //     type: 'info',
                //     message: 'Canceled'
                // });       
            });
        },
        fetchDayOff() {
            this.loading = true;
            axios.get(route('api.company.company.dayoff.index', {company: this.company_id}))
            .then(response => {
                this.day_off = response.data.data
                this.loading = false;
            })
            .catch(err => {
                console.log(err)
            })
        },
        findDayOf(day) {
            return this.day_off.find(element => element.tgl_libur === day)
        }
    },
    mounted() {
        this.fetchDayOff()
    },
}
</script>

<style>
    .el-calendar-day__child {
        height:100%;
        width:100%;
        padding:8px;
    }
</style>