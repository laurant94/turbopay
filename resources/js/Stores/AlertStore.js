import { defineStore } from "pinia";

export const useAlertStore = defineStore("alert", {
    state: ()=>{
        return {
            message: null,
            type: "info"
        }
    },
    getters: {
        getMessage: (state)=> state.message,
        getType: (state)=> state.type
    },
    actions: {
        setMessage(val){
            this.message = val.message;
            this.type = val.type ?? 'info'
            
        },
        clearMessage(val){
            this.message = null
        },
        setType(val){
            this.type = val
        }
    }
})