import React from 'react';

import { Button } from '@/components/ui/button';

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  onPageChange: (page: number) => void;
  isLoading: boolean;
}

const Pagination: React.FC<PaginationProps> = ({
  currentPage,
  totalPages,
  onPageChange,
  isLoading,
}) => (
  <div className="flex justify-center items-center mt-6 space-x-2">
    <Button
      onClick={() => onPageChange(currentPage - 1)}
      disabled={currentPage === 1 || isLoading}
      className="bg-gray-200 text-gray-700 hover:bg-gray-300"
    >
      Anterior
    </Button>
    <span className="text-gray-600">
      Página {currentPage} de {totalPages}
    </span>
    <Button
      onClick={() => onPageChange(currentPage + 1)}
      disabled={currentPage === totalPages || isLoading}
      className="bg-gray-200 text-gray-700 hover:bg-gray-300"
    >
      Siguiente
    </Button>
  </div>
);

export default Pagination;
