import { cva, type VariantProps } from 'class-variance-authority'

export { default as Button } from './Button.vue'

export const buttonVariants = cva(
  'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*=\'size-\'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
  {
    variants: {
      variant: {
        default: 'bg-primary text-primary-foreground shadow-xs hover:bg-primary/90',
        destructive: 'bg-destructive text-white shadow-xs hover:bg-destructive/90 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 dark:bg-destructive/60',
        outline: 'border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50',
        secondary: 'bg-secondary text-secondary-foreground shadow-xs hover:bg-secondary/80',
        ghost: 'hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50',
        link: 'text-primary underline-offset-4 hover:underline',
        'outline-default': 'border border-primary/90 text-primary/90 bg-transparent shadow-xs hover:bg-primary/5 hover:text-primary',
        'outline-destructive': 'border border-destructive text-destructive bg-transparent shadow-xs hover:bg-destructive/10 hover:text-destructive dark:hover:bg-destructive/20',

        primary: 'bg-blue-600 text-white shadow-xs hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600',
        secondary_bs: 'bg-gray-600 text-white shadow-xs hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600',
        success: 'bg-green-600 text-white shadow-xs hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600',
        danger: 'bg-red-600 text-white shadow-xs hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600',
        warning: 'bg-yellow-500 text-black shadow-xs hover:bg-yellow-600 dark:bg-yellow-400 dark:hover:bg-yellow-500',
        info: 'bg-cyan-600 text-white shadow-xs hover:bg-cyan-700 dark:bg-cyan-500 dark:hover:bg-cyan-600',
        light: 'bg-gray-100 text-gray-800 shadow-xs hover:bg-gray-200 dark:bg-gray-200 dark:hover:bg-gray-300',
        dark: 'bg-gray-900 text-white shadow-xs hover:bg-gray-800 dark:bg-gray-800 dark:hover:bg-gray-700',
      },
      size: {
        default: 'h-9 px-4 py-2 has-[>svg]:px-3',
        sm: 'h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5',
        lg: 'h-10 rounded-md px-6 has-[>svg]:px-4',
        icon: 'size-9',
      },
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
    },
  },
)

export type ButtonVariants = VariantProps<typeof buttonVariants>
