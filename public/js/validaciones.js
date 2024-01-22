//DE MOMENTO NO UTILIZAMOS ESTE ARCHIVO
window.Validacion = (function () {
    var Validacion = {    
        validarUsuario: (idUsuario) => {
            return new Promise((resolve, reject) => {
                if(!idUsuario){
                    return reject()
                }else{
                    resolve();
                }
            })
        },
    }
    return Validacion;
})()