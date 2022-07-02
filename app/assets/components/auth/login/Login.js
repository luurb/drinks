import React from 'react';

const Login = () => {
   return (
      <div className="auth">
         <div className="auth__img"></div>
         <div className="auth__box">
            <div className="auth__form-box">
               <div className="auth__header">Wejdź do baru</div>
               <form>
                  <div className="auth__input-box">
                     <label htmlFor="login">Nazwa baristy</label>
                     <input type="text" id="login" name="login"></input>
                  </div>
                  <div className="auth__input-box">
                     <label htmlFor="password">Hasło</label>
                     <input type="text" id="password" name="password"></input>
                  </div>
                  <div className="auth__text-wrapper">
                     <div className="auth__text-box">
                        Pierwszy raz?
                        <span>Otwórz bar</span>
                     </div>
                     <div className="auth__text-box">
                        Nie pamiętasz hasła?
                        <span>Resetuj</span>
                     </div>
                  </div>
                  <input type="submit" value="Wejdź" className="auth__submit"></input>
               </form>
            </div>
         </div>
      </div>
   );
};

export default Login;
