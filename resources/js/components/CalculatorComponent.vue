<template>
  <div class="container">
      <div class="row">
          <div class="col-lg-12">
               <div class="row mt-4">
                <div class="col-lg-6 offset-lg-3">
                    <div class="d-flex justify-content-center">
                        <div class="calculator">
                            <div class="answer">{{ answer }}</div>
                            <div class="display">{{ logList + current }}</div>
                            <div @click="clear" id="clear" class="btn operator">C</div>
                            <div @click="percent" id="percent" class="btn operator">
                                %
                            </div>
                            <div @click="divide" id="divide" class="btn operator">
                                /
                            </div>
                             <div @click="times" id="times" class="btn operator">*</div>
                            <div @click="append('7')" id="n7" class="btn">7</div>
                            <div @click="append('8')" id="n8" class="btn">8</div>
                            <div @click="append('9')" id="n9" class="btn">9</div>
                              <div @click="minus" id="minus" class="btn operator">-</div>
                            <div @click="append('4')" id="n4" class="btn">4</div>
                            <div @click="append('5')" id="n5" class="btn">5</div>
                            <div @click="append('6')" id="n6" class="btn">6</div>
                             <div @click="plus" id="plus" class="btn operator">+</div>
                            <div @click="append('1')" id="n1" class="btn">1</div>
                            <div @click="append('2')" id="n2" class="btn">2</div>
                            <div @click="append('3')" id="n3" class="btn">3</div>
                            <div @click="equal" id="equal" class="btn operator equal">=</div>
                            <div @click="append('0')" id="n0" class="zero">0</div>
                            <div @click="dot" id="dot" class="btn">.</div>
                        </div>
                    </div>
                </div>
               </div>
               <!-- for history -->
               <div class="history mt-3">
                   <h2 class="ticker--title">Ticker tape</h2>

                   <table class="table table-striped table-bordered mt-2 table-centered">
                       <tbody>
                           <tr v-for="history in histories.data" :key="history.id" class="text-center">
                               <td>{{ history.query }}</td>
                               <td>{{ history.answer }}</td>
                               <td>
                                   <div class="d-flex justify-content-center">
                                       <button class="btn btn-danger btn-sm"  @click="deleteHistory(history.id)">Delete</button></div></td>
                           </tr>
                       </tbody>
                   </table>
                   <pagination :data="histories" @pagination-change-page="loadData"></pagination>
               </div>
          </div>
      </div>
  </div>
</template>

<script>
import anime from 'animejs/lib/anime.es.js';
export default {
    props: ['ip'],
    data() {
        return {
            logList: "",
            current: "0",
            answer: "",
            operatorClicked: true,
            histories: []
        };
    },
    methods: {
        append(number) {
            if (this.operatorClicked) {
                this.current = "";
                this.operatorClicked = false;
            }
            if (this.current === 0) {
                this.current = "";
            }
            this.animateNumber(`n${number}`);
            this.current = `${this.current}${number}`;
        },
        addtoLog(operator) {
            if (this.operatorClicked == false) {
                this.logList += `${this.current} ${operator} `;
                this.current = "";
                this.operatorClicked = true;
            }
        },
        animateNumber(number) {
            let tl = anime.timeline({
                targets: `#${number}`,
                duration: 250,
                easing: "easeInOutCubic"
            });
            tl.add({ backgroundColor: "#c1e3ff" });
            tl.add({ backgroundColor: "#f4faff" });
        },
        animateOperator(operator) {
            let tl = anime.timeline({
                targets: `#${operator}`,
                duration: 250,
                easing: "easeInOutCubic"
            });
            tl.add({ backgroundColor: "#a6daff" });
            tl.add({ backgroundColor: "#d9efff" });
        },
        clear() {
            this.animateOperator("clear");
            this.current = "";
            this.answer = "";
            this.logList = "";
            this.operatorClicked = false;
        },
        percent() {
            this.animateOperator("percent");
            if (this.current != "") {
                this.current = `${parseFloat(this.current) / 100}`;
            }
        },
        dot() {
            this.animateNumber("dot");
            if (this.current.indexOf(".") === -1) {
                this.append(".");
            }
        },
        divide() {
            this.animateOperator("divide");
            this.addtoLog("/");
        },
        times() {
            this.animateOperator("times");
            this.addtoLog("*");
        },
        minus() {
            this.animateOperator("minus");
            this.addtoLog("-");
        },
        plus() {
            this.animateOperator("plus");
            this.addtoLog("+");
        },
        equal() {
            this.animateOperator("equal");
            if (this.operatorClicked == false) {
                this.answer = eval(this.logList + this.current);
                const query =   this.logList + this.current;
                console.log('asdfl');
                if(this.answer != ''){
                    let data  = {
                        ip: this.ip,
                        answer: this.answer,
                        query
                    }
                    axios.post(`/api/history`, data).then( res =>{
                        this.loadData();
                    })
                }
            } else {
                this.answer = "WHAT?!!";
            }
        },
        deleteHistory(id){
          if(confirm('Are you sure you want to delete!')){
            axios.delete(`/api/history/delete/${id}`).then( res =>{
             this.loadData();
            })
          }else{
              return;
          }
        },
        loadData(page = 1){
             axios.get(`/api/history/${this.ip}?page=${page}`).then( res =>{
                this.histories = res.data;
            })
        },
    },
    mounted(){
       this.loadData();
    }
}
</script>

<style>

</style>
