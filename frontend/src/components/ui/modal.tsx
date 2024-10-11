import { Card, CardContent, CardFooter, CardHeader, CardTitle } from './card';

import { CloseIcon } from '@/assets/icons/CloseIcon';

interface ModalProps {
  onClose: () => void;
  title?: string; // Título opcional
  width?: string; // Ancho opcional con valor por defecto
  children: React.ReactNode; // Contenido del modal
  footerContent?: React.ReactNode; // Contenido opcional del footer
  contentClassname?: string; // Classname opcional con valor por defecto
  showCloseButton?: boolean; // Mostrar botón de cierre o no
  showHeaderBorder?: boolean; // Mostrar borde en el header o no
  showFooterBorder?: boolean; // Mostrar borde en el footer o no
}

export const Modal: React.FC<ModalProps> = ({
  onClose,
  title,
  width = 'w-96', // Ancho por defecto si no se pasa por prop
  children,
  footerContent,
  contentClassname = 'pt-6 pb-2',
  showCloseButton = true, // Por defecto, mostrar el botón de cierre
  showHeaderBorder = true, // Por defecto, mostrar el borde del header
  showFooterBorder = true, // Por defecto, mostrar el borde del footer
}) => {
  return (
    <div
      className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
      onClick={onClose}
    >
      {/* prettier-ignore */}
      <Card
        className={`bg-white rounded-lg ${width}`}
        onClick={(e) => e.stopPropagation()} // Evita que el modal se cierre al hacer clic dentro de él
      >
        <CardHeader
          className={`px-4 py-6 flex flex-row items-center justify-between ${showHeaderBorder ? 'border-b border-gray-300' : ''
            }`}
        >
          {title && (
            <CardTitle className="text-lg font-semibold">{title}</CardTitle>
          )}
          {showCloseButton && ( // Mostrar botón de cierre si se especifica
            <button
              onClick={onClose}
              className="text-gray-500 hover:text-gray-700 transition-colors"
              aria-label="Cerrar modal"
            >
              <CloseIcon className="h-5 w-5" />
            </button>
          )}
        </CardHeader>

        <CardContent className={contentClassname}>{children}</CardContent>

        {footerContent && (
          <CardFooter
            className={`w-full px-4 py-6 flex justify-end space-x-4 ${showFooterBorder ? 'border-t border-gray-300' : ''
              }`}
          >
            {footerContent}
          </CardFooter>
        )}
      </Card>
    </div>
  );
};
