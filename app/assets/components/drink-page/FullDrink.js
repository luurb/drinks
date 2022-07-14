import React from 'react';

const FullDrink = ({ drink }) => {
   return (
      <div className="full-drink">
         {console.log(drink)}
         <div className="full-drink__wrapper">
            <img
               className="full-drink__img"
               src={drink.image}
               alt="Zdjęcie drinka"
               loading="lazy"
            />
            <div className="full-drink__text-box">
               <div className="full-drink__name">
                  {drink.categories.map((category, index) => (
                     <div
                        key={index}
                        className="full-drink__disc"
                        style={{ background: `var(--red)` }}
                     ></div>
                  ))}
                  {drink.name}
               </div>
               <div className="full-drink__products">
                  <span>Składniki:</span>
                  <div className="drink__products-box">
                     {drink.products.map((product) => (
                        <div
                           key={product.id}
                           className="drink__product full-drink__product"
                        >
                           {product.name}
                        </div>
                     ))}
                  </div>
               </div>
               <p className="full-drink__desc">{drink.description}</p>
               <div className="full-drink__header">Przygotowanie</div>
               <ul className="full-drink__list full-drink__desc">
                  <li>
                     Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                     aliquam urna ac sapien tristique scelerisque. Suspendisse
                     potenti. Lorem ipsum dolor sit amet, consectetur adipiscing
                     elit. Ut tristique turpis vitae elementum volutpat. Sed id
                     imperdiet leo, at euismod lacus. Suspendisse convallis
                     metus at orci condimentum, nec vestibulum risus tempor.
                  </li>
                  <li>
                     Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                     aliquam urna ac sapien tristique scelerisque. Suspendisse
                     potenti. Lorem ipsum dolor sit amet, consectetur adipiscing
                     elit. Ut tristique turpis vitae elementum volutpat. Sed id
                     imperdiet leo, at euismod lacus.
                  </li>
                  <li>
                     Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                     aliquam urna ac sapien tristique scelerisque. Suspendisse
                     potenti. Lorem ipsum dolor sit amet, consectetur adipiscing
                     elit. Ut tristique turpis vitae elementum volutpat. Sed id
                     imperdiet leo, at euismod lacus. elementum volutpat. Sed id
                     imperdiet leo, at euismod
                  </li>
               </ul>
               <div className="full-drink__header full-drink__rating-header">
                  Oceny od barmanów
               </div>
               <div className="full-drink__rating-box">
                  <div className="full-drink__rating">
                     <i className="fa-solid fa-whiskey-glass"></i>
                     <i className="fa-solid fa-whiskey-glass"></i>
                     <i className="fa-solid fa-whiskey-glass"></i>
                     <span>3 na 5 ocen</span>
                  </div>
                  <div className="full-drink__rating-wrapper">
                     <div className="full-drink__specific-rating">
                        <div className="full-drink__rating">
                           <i className="fa-solid fa-whiskey-glass"></i>
                           <span>
                              5
                              <span className="full-drink__rating-counter">
                                 (3)
                              </span>
                           </span>
                        </div>
                        <div className="full-drink__rating">
                           <i className="fa-solid fa-whiskey-glass"></i>
                           <span>
                              4
                              <span className="full-drink__rating-counter">
                                 (3)
                              </span>
                           </span>
                        </div>
                        <div className="full-drink__rating">
                           <i className="fa-solid fa-whiskey-glass"></i>
                           <span>
                              3
                              <span className="full-drink__rating-counter">
                                 (3)
                              </span>
                           </span>
                        </div>
                        <div className="full-drink__rating">
                           <i className="fa-solid fa-whiskey-glass"></i>
                           <span>
                              2
                              <span className="full-drink__rating-counter">
                                 (3)
                              </span>
                           </span>
                        </div>
                        <div className="full-drink__rating">
                           <i className="fa-solid fa-whiskey-glass"></i>
                           <span>
                              1
                              <span className="full-drink__rating-counter">
                                 (3)
                              </span>
                           </span>
                        </div>
                     </div>
                     <div className="full-drink__specific-rating full-drink__bars-box">
                        <div className="full-drink__rating-bar"></div>
                        <div className="full-drink__rating-bar"></div>
                        <div className="full-drink__rating-bar"></div>
                        <div className="full-drink__rating-bar"></div>
                     </div>
                  </div>
               </div>
               <div className="full-drink__header full-drink__comments-header">
                  Komentarze
               </div>
               <div className="full-drink__comments-box">
                  <div className="full-drink__comment-header">
                     <div className="full-drink__comment-rating-box">
                        <i className="fa-solid fa-whiskey-glass"></i>
                        <i className="fa-solid fa-whiskey-glass"></i>
                        <i className="fa-solid fa-whiskey-glass"></i>
                        <i className="fa-solid fa-whiskey-glass white"></i>
                        <i className="fa-solid fa-whiskey-glass white"></i>
                     </div>
                     <span>kaczka6</span>
                  </div>
                  <div className="full-drink__comment-title-box">
                     <span>Pyszny</span>
                     <span className="full-drink__comment-date">
                        22.07.2022 15:34
                     </span>
                  </div>
                  <div className="full-drink__comment">
                     Lorem ipsum dolor sit amet, consectetur adipiscing elit. In
                     aliquam urna ac sapien tristique scelerisque. Suspendisse
                     potenti. Lorem ipsum dolor sit amet, consectetur adipiscing
                     elit. Ut tristique turpis vitae elementum volutpat. Sed id
                     imperdiet leo, at euismod lacus. elementum volutpat. Sed id
                     imperdiet leo, at euismod
                  </div>
               </div>
            </div>
         </div>
      </div>
   );
};

export default FullDrink;
