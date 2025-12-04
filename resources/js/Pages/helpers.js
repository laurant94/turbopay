
export const asset = (path)=>{
  return import.meta.env.VITE_APP_URL +'/' + path;
}