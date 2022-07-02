import React from 'react';
import { Link } from 'react-router-dom';

const SingUp = () => {
   return (
      <div className="auth">
         <div className="auth__img"></div>
         <div className="auth__box">
            <div className="auth__form-box">
               <div className="auth__header">Otwórz swój bar</div>
               <form>
                  <div className="auth__inputs-wrapper">
                     <div className="auth__inputs-box">
                        <div className="auth__input-box">
                           <label htmlFor="login">Nazwa baristy</label>
                           <input type="text" id="login" name="login"></input>
                        </div>
                        <div className="auth__input-box">
                           <label htmlFor="email">Email</label>
                           <input type="email" id="email" name="email"></input>
                        </div>
                     </div>
                     <div className="auth__inputs-box">
                        <div className="auth__input-box">
                           <label htmlFor="password">Hasło</label>
                           <input
                              type="password"
                              id="password"
                              name="password"
                           ></input>
                        </div>
                        <div className="auth__input-box">
                           <label htmlFor="confirm_password">
                              Powtórz hasło
                           </label>
                           <input
                              type="password"
                              id="confirm_password"
                              name="confirm_password"
                           ></input>
                        </div>
                     </div>
                  </div>
                  <div className="auth__text-wrapper">
                     <div className="auth__text-box">
                        Masz już swój bar?
                        <Link to="/login">
                           <span>Wejdź</span>
                        </Link>
                     </div>
                  </div>
                  <input
                     type="submit"
                     value="Stwórz bar"
                     className="auth__submit"
                  ></input>
               </form>
            </div>
         </div>
      </div>
   );
};

export default SingUp;
