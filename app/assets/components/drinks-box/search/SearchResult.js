import React from 'react';

const SearchResult = ({product, index, onClick}) => {
   return (
      <div
         key={product.id}
         className="search__result"
         style={{
            background: index % 2 ? 'var(--white)' : 'var(--yellow)',
         }}
         onClick={() => onClick(product)}
      >
         {product.name}
      </div>
   );
};

export default SearchResult;
