import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const Login = () => {
   const [inputs, setInputs] = useState({
      name: '',
      password: '',
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
               <div className="auth__header">Wejdź do baru</div>
               <form>
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
                     <label htmlFor="password">Hasło</label>
                     <input
                        type="password"
                        id="password"
                        name="password"
                        onChange={handleInput}
                     ></input>
                  </div>
                  <div className="auth__text-wrapper">
                     <div className="auth__text-box">
                        Pierwszy raz?
                        <Link to="/singup">
                           <span>Otwórz bar</span>
                        </Link>
                     </div>
                     <div className="auth__text-box">
                        Nie pamiętasz hasła?
                        <span>Resetuj</span>
                     </div>
                  </div>
                  <input
                     type="submit"
                     value="Wejdź"
                     className="auth__submit"
                  ></input>
               </form>
            </div>
         </div>
      </>
   );
};

export default Login;
