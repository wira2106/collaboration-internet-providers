<template>
    <Card class="card card-outline-info">
        <div class="card-header">
             <div class="card-actions">
                 <el-button
                    size="small"
                    :type="graph ? 'secondary' : 'success'"
                    @click="graph = !graph"
                >
                    {{ !graph ? trans('analytics.title.graphic') : trans('analytics.title.text') }}
                </el-button>
                <a data-action="collapse">
                    <i class="ti-minus text-white"></i>
                </a>
            </div>
            <h5 class="text-white mx-2">
                {{title}}
            </h5>
        </div>
        <div class="card-body show collapse">
            <div class="table-responsive">
                <table
                class="table small mb-0"
                style="width: 100%;"
                >
                <tbody>
                    <tr v-for="(val, index) in data" :key="index">
                    <td style="width: 15px">
                        {{
                        val.type != "isp" && val.type != "osp"
                            ? "#"
                            : index + 1
                        }}
                    </td>
                    <td style="width: 175px">{{ val.nama_activity }}</td>
                    <td style="width: 10px">:</td>
                    <td>
                        {{ !graph ? generateContentTooltip(val.total_waktu, val.type) : '' }}

                        <el-tooltip
                            class="item"
                            effect="dark"
                            :content="
                              generateContentTooltip(val.total_waktu, val.type)
                            "
                            placement="top-end"
                            v-if="graph"
                          >
                            <div
                              :class="
                                'progress-bar progress-activity ' +
                                  generateClass(val.type)
                              "
                              :id="`progress-${title}-${index}`"
                            ></div>
                          </el-tooltip>
                    </td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </Card>
</template>

<script>
import Card from '../../../../../Core/Assets/js/components/Card.vue'

export default {
    props: {
        data: {
            default: () => {
                return []
            },
        },
        title: {
            default:''
        },
        pelanggan: {
            default: null
        }
    },
    components: {
        Card
    },
    data() {
        return {
            graph: false
        }
    },
    methods: {
        changeType(type) {
            this.type = type
        },
        generateClass(type) {
            if (type == "osp" || type == "isp" || type == "estimasi") {
                return type;
            } else {
                let total = 0;
                let estimasi = 0;
                this.data.forEach((val) => {
                if (val.type == "total") {
                    total = val.total_waktu;
                } else if (val.type == "estimasi") {
                    estimasi = val.total_waktu;
                }
                });

                if (total > estimasi) {
                return "bg-danger";
                } else {
                return "bg-success";
                }
            }
        },
        generateContentTooltip(time, type) {
            if (type != "total") {
                return this.convertToTime(time);
            } else {
                let estimasi = parseInt(this.pelanggan.estimasi_instalasi);
                let total = parseInt(time);
                if (total > estimasi) {
                return (
                    this.convertToTime(time) +
                    " ( lebih " +
                    this.convertToTime(total - estimasi) +
                    ")"
                );
                } else {
                return this.convertToTime(time);
                }
            }
        },
        generateBar() {
            let vm = this;
            setTimeout(() => {
                for (var i = 0; i < vm.data.length; i++) {
                $(`#progress-${this.title}-${i}`).css(
                    "width",
                    vm.convertPercentage(parseInt(vm.data[i].total_waktu))
                );
                }
            }, 100);
        },
        convertPercentage(value) {
            let timer = [];
            this.data.forEach((element) => {
                timer.push(parseInt(element.total_waktu));
            });
            let tertinggi = Math.max(...timer);
            let percentage = (value / tertinggi) * 655;

            return percentage + "px";
        },
        convertToTime(value) {
            let time = "";
            let hari = parseInt(value / 86400);
            let jam = parseInt((value % 86400) / 3600);
            let menit = parseInt(((value % 86400) % 3600) / 60);
            let detik = ((value % 86400) % 3600) % 60;

            if (hari > 0) {
                time += hari + " Hari ";
            }
            if (jam > 0) {
                time += jam + " Jam ";
            }
            if (menit > 0) {
                time += menit + " Menit ";
            }
            if (detik > 0) {
                time += detik + " Detik";
            }

            return time;
        },
    },
    mounted() {
        this.generateBar();
    },
    watch: {
        graph() {
            if(this.graph) {
                this.generateBar();
            }
        }
    }
    
}
</script>

<style>

</style>