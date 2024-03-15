import{T as d,o as r,e as m,b as e,u as t,w as o,F as c,Z as f,t as _,f as p,a,n as w,g,l as b}from"./app-DeJr7fNP.js";import{A as y}from"./AuthenticationCard-D7aKXzjD.js";import{_ as x}from"./AuthenticationCardLogo-BQ4D_qzY.js";import{_ as h,a as k}from"./InputLabel-C2gVT93H.js";import{_ as V}from"./PrimaryButton-Fk5jf8Nh.js";import{_ as v}from"./TextInput-BUWn7uYR.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const F=a("div",{class:"mb-4 text-sm text-gray-600"}," Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",-1),N={key:0,class:"mb-4 font-medium text-sm text-green-600"},$=["onSubmit"],C={class:"flex items-center justify-end mt-4"},z={__name:"ForgotPassword",props:{status:String},setup(l){const s=d({email:""}),n=()=>{s.post(route("password.email"))};return(S,i)=>(r(),m(c,null,[e(t(f),{title:"Forgot Password"}),e(y,null,{logo:o(()=>[e(x)]),default:o(()=>[F,l.status?(r(),m("div",N,_(l.status),1)):p("",!0),a("form",{onSubmit:b(n,["prevent"])},[a("div",null,[e(h,{for:"email",value:"Email"}),e(v,{id:"email",modelValue:t(s).email,"onUpdate:modelValue":i[0]||(i[0]=u=>t(s).email=u),type:"email",class:"mt-1 block w-full",required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),e(k,{class:"mt-2",message:t(s).errors.email},null,8,["message"])]),a("div",C,[e(V,{class:w({"opacity-25":t(s).processing}),disabled:t(s).processing},{default:o(()=>[g(" Email Password Reset Link ")]),_:1},8,["class","disabled"])])],40,$)]),_:1})],64))}};export{z as default};
