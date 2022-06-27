import React from 'react';

const DrinksBoxSkeleton = () => {
   return (
      <div className="drinks">
         <div className="drinks__top">
            <span className="drinks__counter"></span>
            <div className="drinks__sort-box">
               <span></span>
            </div>
         </div>
         <div className="drinks__wrapper">
            <div className="drink">
               <div className="drink__top">
                  <div className="drink__name-box">
                     <div className="drink__categories-box">
                        <div className="drink__category-disc"></div>
                     </div>
                     <span className="drink__name"></span>
                  </div>
                  {/*social wrapper*/}
               </div>
               <div className="drink__bottom">
                  <div className="drink__img drink__skeleton-img"></div>
                  <div className="drink__desc">
                     <div className="drink__desc-text"></div>
                     <div className="drink__products-wrapper">
                        <span className="drink__products-header"></span>
                        <div className="drink__products-box"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   );
};

export default DrinksBoxSkeleton;
