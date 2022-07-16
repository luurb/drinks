import React, { useEffect } from 'react';
import { useLocation } from 'react-router-dom';

const FullDrink = ({ drink }) => {
   const location = useLocation();
   const { products } = location.state || {};
   const categoriesColors = [
      {
         name: 'słodki',
         color: 'yellow',
      },
      {
         name: 'kwaśny',
         color: 'green',
      },
      {
         name: 'orzeźwiający',
         color: 'blue',
      },
      {
         name: 'lekki',
         color: 'turquoise',
      },
      {
         name: 'mocny',
         color: 'red',
      },
   ];

   const getCategoryColor = (categoryToFind) =>
      categoriesColors.find((category) => category.name == categoryToFind.name)
         .color;

   const getReviewText = (reviewsNumber) => {
      switch (reviewsNumber) {
         case 1:
            return 'opinia';
         case 2:
         case 3:
         case 4:
            return 'opinie';
         default:
            return 'opinii';
      }
   };

   const getRatingText = (ratingNumber) => {
      switch (ratingNumber) {
         case 1:
            return 'ocena';
         case 2:
         case 3:
         case 4:
            return 'oceny';
         default:
            return 'ocen';
      }
   };

   return (
      <div className="full-drink">
         {console.log(drink)}
         {console.log(products)}
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
                        style={{
                           background: `var(--${getCategoryColor(category)})`,
                        }}
                     ></div>
                  ))}
                  {drink.name}
               </div>
               <div className="full-drink__social-wrapper">
                  <span className="full-drink__author">
                     @{drink.author.username}
                  </span>
                  <div className="drink__social-box">
                     <i className="fa-solid fa-whiskey-glass"></i>
                     <span>{drink.ratingsNumber}</span>
                     <span className="drink__rate-text d-block">
                        {getRatingText(drink.ratingsNumber)}
                     </span>
                  </div>
                  <div className="drink__social-box">
                     <i className="fa-solid fa-comment"></i>
                     <span>{drink.reviewsNumber}</span>
                     <span className="drink__comment-text d-block">
                        {getReviewText(drink.reviewsNumber)}
                     </span>
                  </div>
               </div>
               <div className="full-drink__products">
                  <span>Składniki:</span>
                  <div className="drink__products-box">
                     {products
                        ? products.map((product) => (
                             <div
                              key={product.id}
                              className={
                                 product.active
                                    ? 'drink__active-product full-drink__product'
                                    : 'drink__product full-drink__product'}
                             >
                                {product.name}
                             </div>
                          ))
                        : drink.products.map((product) => (
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
                  Opinie
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
