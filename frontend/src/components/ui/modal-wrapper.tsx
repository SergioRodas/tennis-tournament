import React from 'react';

import { Dialog, DialogContent } from '@/components/ui/dialog';

interface ModalWrapperProps {
  isOpen: boolean;
  onClose: () => void;
  children: React.ReactNode;
}

const ModalWrapper: React.FC<ModalWrapperProps> = ({
  isOpen,
  onClose,
  children,
}) => {
  return (
    <Dialog open={isOpen} onOpenChange={(open) => !open && onClose()}>
      <DialogContent>{children}</DialogContent>
    </Dialog>
  );
};

export default ModalWrapper;
