import{o as u,e as c,a as e,d as h,b5 as k,C as w,b as n,w as g,u as d,k as y,G as _,m as V,J as $,F as C,h as j,l as x,t as M}from"./app-DeJr7fNP.js";import{_ as B,a as T}from"./InputLabel-C2gVT93H.js";import{T as z}from"./Tooltip-CSg56dN2.js";import{r as A}from"./QuestionMarkCircleIcon-jeUFMgsk.js";import{r as K}from"./PlusIcon-NsBgz0vi.js";import{r as D}from"./CartItemButton-BUsVs_zz.js";function F(l,m){return u(),c("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"}),e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 6h.008v.008H6V6z"})])}const H={class:"flex items-center"},L={class:"relative mb-2 flex flex-wrap items-stretch"},N={class:"flex bg-gray-100 text-gray-500 items-center whitespace-nowrap rounded-s border border-e-0 border-solid border-neutral-300 text-center text-base font-normal leading-[1.6] dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200",id:"basic-addon1"},E={class:"p-3"},G=["onKeydown"],I=["id","value"],J={class:"flex items-center"},R=["onClick"],S={class:"px-2"},Z={__name:"TagInput",props:["modelValue","type","id","classes","placeholder","error"],emits:["update:modelValue"],setup(l,{expose:m,emit:p}){let o=h(null),i=h(null);const s=k([]);w(()=>{i.value.hasAttribute("autofocus")&&i.value.focus()}),m({focus:()=>i.value.focus(),set:a=>{if(a){const t=a.split(",");for(let r in t)s.push(t[r]);p("update:modelValue",s.join(","))}}});const f=()=>{o.value&&(s.push(o.value),o.value=null,p("update:modelValue",s.join(",")))},b=a=>{s.splice(a,1),p("update:modelValue",s.join(","))};return(a,t)=>(u(),c("div",null,[e("div",H,[n(z,{class:"",content:a.__("help_tags")},{default:g(()=>[n(d(A),{class:"text-gray-500 hover:bg-gray-50 w-4 h-4"})]),_:1},8,["content"]),n(B,{for:l.id,value:l.placeholder},null,8,["for","value"])]),e("div",L,[e("span",N,[e("div",E,[n(d(F),{class:"h-5 w-5"})])]),y(e("input",{"onUpdate:modelValue":t[0]||(t[0]=r=>V(o)?o.value=r:o=r),onKeydown:$(x(f,["prevent"]),["enter"]),class:"flex-auto rounded-0 border border-solid border-neutral-300 px-3 text-neutral-700 transition duration-200 ease-in-out focus:z-[3] focus:border-primary-500 focus:text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary",onVisibility:t[1]||(t[1]=r=>a.$el.type="text"),ref_key:"input",ref:i},null,40,G),[[_,d(o)]]),e("input",{id:l.id,type:"hidden",value:l.modelValue,onClick:t[2]||(t[2]=r=>a.$emit("update:modelValue",r.target.value))},null,8,I),e("span",{onClick:f,class:"flex cursor-pointer hover:bg-primary-400 bg-primary-500 items-center whitespace-nowrap rounded-e border border-s-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200",id:"basic-addon2"},[n(d(K),{class:"h-5 w-5 text-white font-bold"})])]),n(T,{class:"mt-1",message:l.error},null,8,["message"]),e("div",J,[(u(!0),c(C,null,j(s,(r,v)=>(u(),c("span",null,[e("button",{onClick:x(q=>b(v),["prevent"]),class:"flex mx-[1px] items-center bg-primary-500 hover:bg-primary-600 rounded p-1 text-sm text-white"},[n(d(D),{class:"h-5 w-5 font-bold"}),e("span",S,M(r),1)],8,R)]))),256))])]))}};export{Z as _};
