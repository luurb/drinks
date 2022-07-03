import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const SingUp = () => {
   const [inputs, setInputs] = useState({
      name: '',
      email: '',
      password: '',
      confirmed_password: '',
   });

   const handleInput = (e) => {
      const target = e.target;
      const name = target.name;

      setInputs({
         ...inputs,
         [name]: target.value,
      });
   };

   return (
      <>
         <div className="auth__img"></div>
         <div className="auth__box">
            <div className="auth__form-box">
               <div className="auth__header">Otwórz swój bar</div>
               <form>
                  <div className="auth__inputs-wrapper">
                     <div className="auth__inputs-box">
                        <div className="auth__input-box">
                           <label htmlFor="name">Nazwa barmana</label>
                           <input
                              type="text"
                              id="name"
                              name="name"
                              value={inputs.name}
                              onChange={handleInput}
                           ></input>
                        </div>
                        <div className="auth__input-box">
                           <label htmlFor="email">Email</label>
                           <input
                              type="email"
                              id="email"
                              name="email"
                              value={inputs.email}
                              onChange={handleInput}
                           ></input>
                        </div>
                     </div>
                     <div className="auth__inputs-box">
                        <div className="auth__input-box">
                           <label htmlFor="password">Hasło</label>
                           <input
                              type="password"
                              id="password"
                              name="password"
                              onChange={handleInput}
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
                              onChange={handleInput}
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
      </>
   );
};

export default SingUp;
