<?php

declare(strict_types = 1);

use Tak\Liveproto\Utils\Logging;

use Tak\Liveproto\Network\Client;

use Tak\Liveproto\Enums\Authentication;

defined('LOGO_SVG') || define('LOGO_SVG',<<<SVG
<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="288.58 338.44 595.97 535.54">
	<path fill="#227ca0" d="   M 432.12 417.70   C 432.25 422.40 432.54 425.95 431.41 431.14   Q 429.95 435.17 428.51 439.12   Q 425.00 448.72 417.93 455.08   Q 412.15 460.28 401.86 464.60   A 2.83 2.73 31.2 0 1 400.85 464.83   Q 399.36 464.87 397.49 465.36   Q 389.79 467.37 381.92 466.24   Q 359.72 463.04 348.02 443.51   Q 344.23 437.19 343.09 430.26   Q 341.93 423.14 343.04 414.52   L 346.18 400.48   Q 351.76 385.29 360.37 371.59   C 365.93 362.75 374.85 349.97 382.64 340.74   Q 385.78 337.02 390.37 339.32   Q 392.02 340.15 394.15 343.12   C 405.08 358.35 413.39 369.65 421.55 384.96   Q 428.00 397.07 430.80 410.79   Q 431.53 414.34 432.12 417.70   Z   M 388.08 352.97   A 0.75 0.75 0.0 0 0 387.13 353.15   Q 372.55 371.28 361.87 391.62   Q 355.88 403.02 353.40 415.08   C 349.95 431.89 359.13 448.03 374.75 454.17   C 383.76 457.71 393.87 457.52 402.51 453.26   Q 419.23 445.02 421.64 426.18   Q 422.09 422.68 421.09 416.46   Q 419.05 403.73 413.75 393.24   C 406.66 379.22 398.20 367.13 388.95 353.84   Q 388.52 353.22 388.08 352.97   Z"></path>
	<path fill="#216623" d="   M 346.18 400.48   L 343.04 414.52   Q 343.19 413.87 343.05 413.45   Q 342.92 413.04 342.24 412.81   Q 321.23 405.40 302.33 393.97   A 0.58 0.58 0.0 0 0 301.46 394.51   Q 302.51 409.85 302.75 415.81   C 303.13 424.96 304.53 433.02 305.57 443.49   Q 306.01 447.91 309.28 465.00   C 311.33 475.72 314.88 487.92 317.73 496.79   Q 323.53 514.83 333.00 532.15   Q 362.36 585.72 416.65 610.79   C 431.31 617.56 448.68 623.11 464.59 626.34   Q 496.19 632.76 513.75 637.30   C 529.99 641.49 545.71 648.16 558.95 658.91   A 0.36 0.36 0.0 0 0 559.53 658.67   Q 559.63 657.56 559.02 656.00   C 550.14 633.24 535.40 612.00 518.47 595.79   Q 516.79 594.17 514.12 594.12   Q 509.76 594.02 504.62 593.41   C 484.81 591.08 467.08 588.43 448.86 583.67   Q 436.86 580.54 430.66 578.55   Q 414.47 573.34 395.38 565.61   Q 393.49 564.85 384.90 560.58   A 0.20 0.20 0.0 0 1 384.82 560.29   Q 384.87 560.21 385.04 560.14   Q 385.26 560.06 385.48 560.15   Q 387.59 561.09 388.04 561.20   Q 391.26 562.00 405.33 565.88   Q 414.67 568.45 432.51 571.94   Q 443.30 574.04 466.92 577.19   C 480.22 578.96 491.81 579.52 505.06 580.53   A 0.27 0.27 0.0 0 0 505.31 580.11   Q 504.89 579.44 504.17 578.82   Q 482.87 560.27 465.11 547.95   C 463.06 546.52 460.56 544.57 458.40 544.34   Q 434.40 541.76 421.12 539.12   Q 402.22 535.35 377.65 527.00   C 371.19 524.53 364.74 522.23 358.50 519.38   Q 354.44 517.53 351.32 516.48   Q 350.30 516.14 349.79 515.34   A 0.34 0.34 0.0 0 1 350.08 514.82   C 350.37 514.83 352.64 516.14 353.80 516.44   Q 378.42 522.83 386.50 524.21   Q 389.42 524.71 394.26 525.62   C 408.00 528.22 422.49 529.74 438.97 531.59   A 0.28 0.28 0.0 0 0 439.19 531.10   C 437.84 529.83 435.73 528.80 434.63 528.10   Q 410.59 512.72 387.72 500.78   Q 350.07 481.13 334.37 472.02   A 0.09 0.09 0.0 0 1 334.33 471.91   L 334.34 471.90   A 0.12 0.12 0.0 0 1 334.50 471.84   L 413.61 502.25   A 0.47 0.46 88.8 0 0 414.20 501.63   Q 413.63 500.36 413.15 499.61   Q 402.55 483.00 397.87 473.42   Q 396.51 470.64 396.17 469.99   A 0.54 0.53 -30.0 0 1 396.37 469.27   L 396.38 469.26   A 0.45 0.45 0.0 0 1 396.96 469.37   C 400.22 473.52 404.47 479.56 408.09 484.09   Q 416.63 494.79 423.53 505.02   C 425.25 507.56 426.90 508.97 429.45 510.31   Q 449.41 520.79 468.54 533.11   A 0.65 0.65 0.0 0 0 469.52 532.41   L 458.76 488.55   L 466.97 488.80   Q 465.33 488.70 464.15 489.22   A 0.59 0.58 -21.5 0 0 463.83 489.94   L 480.11 540.03   A 7.61 7.55 9.8 0 0 482.73 543.72   Q 505.88 561.37 526.22 582.05   A 0.46 0.46 0.0 0 0 527.01 581.75   Q 527.17 577.93 526.88 576.12   Q 520.43 535.49 504.22 499.02   Q 504.06 498.65 504.10 498.01   A 0.20 0.19 30.4 0 1 504.46 497.92   Q 516.03 515.90 524.31 536.66   Q 527.41 544.44 530.20 552.27   Q 537.33 574.14 539.76 596.22   A 5.55 5.51 -68.9 0 0 541.10 599.27   Q 555.19 615.24 565.78 632.40   A 0.62 0.61 -61.4 0 0 566.92 632.06   C 566.81 624.93 568.05 617.94 567.92 610.97   Q 567.67 598.28 566.74 584.50   Q 566.40 579.55 565.37 574.59   Q 564.81 571.91 563.66 564.72   Q 562.36 556.60 559.79 548.75   Q 559.27 546.41 558.60 544.13   C 548.42 509.76 528.11 478.57 498.37 458.16   A 0.49 0.49 0.0 0 0 497.62 458.43   Q 497.53 458.75 497.60 459.22   Q 497.59 451.62 497.06 449.23   Q 496.15 445.06 495.28 440.65   C 528.18 461.02 553.13 492.29 566.46 528.27   Q 575.47 552.60 578.58 578.03   C 580.72 595.45 581.38 613.73 580.02 631.61   C 579.09 643.78 578.92 648.65 578.04 655.72   Q 577.54 659.80 578.09 662.69   C 578.82 666.52 579.27 671.13 580.49 675.16   Q 581.09 677.14 581.14 679.03   A 0.38 0.38 0.0 0 0 581.87 679.15   Q 585.10 670.26 588.81 659.59   Q 591.25 652.56 591.40 648.83   C 591.65 642.90 591.94 637.62 591.81 633.39   Q 591.46 621.99 593.05 598.27   C 596.06 553.14 610.43 509.50 637.12 473.15   Q 659.02 443.31 691.41 425.19   Q 713.88 412.62 737.98 405.52   C 752.41 401.27 767.34 397.20 782.50 393.52   C 805.20 388.02 828.52 380.91 848.55 369.90   C 855.53 366.06 864.02 360.32 868.95 355.45   C 871.80 352.64 876.39 347.24 880.55 349.30   Q 883.67 350.85 884.06 354.76   Q 884.31 357.32 884.50 366.75   Q 884.86 384.83 882.89 411.49   Q 880.32 446.15 873.10 478.43   Q 871.58 485.19 867.92 498.21   Q 862.06 518.98 853.26 539.25   C 836.72 577.32 807.40 610.27 769.92 628.16   Q 752.84 636.31 733.69 641.17   Q 715.71 645.73 699.77 648.02   C 682.71 650.46 669.08 652.48 653.38 656.89   C 637.36 661.39 623.33 668.42 612.05 680.26   Q 600.90 691.96 592.34 710.33   Q 584.21 727.77 577.92 748.15   Q 567.58 781.65 559.45 806.70   A 0.34 0.33 75.9 0 0 560.01 807.04   C 573.29 792.86 584.21 780.40 603.03 758.79   C 620.03 739.28 638.67 720.78 660.62 709.11   Q 674.52 701.73 689.09 699.89   Q 697.99 698.77 707.50 701.01   C 728.36 705.93 733.40 727.28 725.52 745.05   C 708.86 782.63 661.83 800.32 623.68 804.79   Q 602.15 807.31 580.18 807.87   A 2.21 2.20 19.6 0 0 578.59 808.62   Q 550.06 841.38 520.67 870.92   Q 518.71 872.89 516.48 873.79   A 2.56 2.44 33.0 0 1 515.47 873.98   C 510.97 873.95 509.75 870.17 511.82 866.81   C 527.20 841.89 539.49 815.18 548.81 787.66   C 555.78 767.06 562.24 744.91 566.01 723.77   C 569.44 704.55 568.03 684.57 552.57 670.91   Q 542.71 662.21 529.31 656.92   Q 514.20 650.94 499.00 647.76   Q 474.50 642.63 455.47 637.99   Q 435.92 633.23 417.39 625.36   Q 369.80 605.16 338.42 564.10   C 306.20 521.95 292.81 468.82 289.72 416.75   Q 289.53 413.53 288.70 394.50   Q 288.41 387.81 288.85 382.39   Q 289.24 377.61 293.22 376.34   C 295.09 375.75 297.37 376.93 298.83 377.93   C 313.75 388.13 329.29 394.63 346.18 400.48   Z   M 633.70 502.73   Q 624.67 518.82 618.66 536.39   Q 607.82 568.06 605.07 601.25   C 604.35 610.02 604.53 617.09 604.29 628.29   A 0.32 0.32 0.0 0 0 604.89 628.46   C 622.16 599.87 643.90 575.08 669.35 552.86   Q 687.39 537.12 704.78 524.09   Q 705.61 524.62 706.20 524.18   Q 741.60 498.29 756.46 487.71   Q 760.90 484.56 764.99 482.70   A 0.62 0.62 0.0 0 1 765.87 483.31   C 765.74 485.26 761.68 489.74 760.03 491.54   Q 741.99 511.23 724.00 530.25   Q 720.76 533.67 721.61 537.54   L 712.97 534.91   A 2.72 2.72 0.0 0 0 710.52 535.35   Q 679.00 559.39 653.14 589.12   Q 625.11 621.33 607.52 659.53   Q 605.20 664.56 603.86 668.82   A 0.32 0.32 0.0 0 0 604.39 669.14   Q 619.71 654.30 639.78 647.08   Q 659.60 639.96 683.48 636.73   Q 706.22 633.65 720.67 630.53   Q 721.05 630.45 723.79 629.85   Q 751.11 623.91 773.99 610.82   Q 795.33 598.61 811.76 579.81   C 827.09 562.27 838.75 541.53 846.78 519.48   Q 855.09 497.11 859.63 476.63   Q 859.95 475.15 862.57 462.58   C 863.32 458.97 863.39 455.89 864.27 452.33   Q 865.08 449.00 865.56 444.64   C 866.33 437.57 867.66 430.82 868.26 423.09   Q 869.03 413.22 869.70 404.99   Q 871.14 387.45 871.11 371.14   A 0.48 0.47 -20.0 0 0 870.33 370.78   C 860.62 378.85 848.91 385.05 838.31 389.34   Q 818.39 397.39 800.42 402.11   C 771.07 409.82 752.98 414.61 734.22 420.50   Q 712.02 427.47 690.68 440.70   C 666.66 455.58 647.49 477.75 633.70 502.73   Z   M 590.60 793.54   Q 606.31 792.38 622.24 789.94   C 649.64 785.74 676.78 776.25 697.44 757.46   C 705.42 750.21 712.39 740.06 712.82 729.10   C 713.33 716.19 700.37 713.02 690.42 714.68   C 672.87 717.61 656.75 727.39 642.73 739.23   Q 629.58 750.36 619.45 761.21   Q 608.99 772.42 590.43 793.19   A 0.21 0.21 0.0 0 0 590.60 793.54   Z"></path>
	<path fill="#62b8e2" d="   M 388.08 352.97   Q 388.52 353.22 388.95 353.84   C 398.20 367.13 406.66 379.22 413.75 393.24   Q 419.05 403.73 421.09 416.46   Q 422.09 422.68 421.64 426.18   Q 419.23 445.02 402.51 453.26   C 393.87 457.52 383.76 457.71 374.75 454.17   C 359.13 448.03 349.95 431.89 353.40 415.08   Q 355.88 403.02 361.87 391.62   Q 372.55 371.28 387.13 353.15   A 0.75 0.75 0.0 0 1 388.08 352.97   Z   M 409.0898 418.5684   A 13.45 8.05 76.5 0 0 413.7776 403.6108   A 13.45 8.05 76.5 0 0 402.8102 392.4116   A 13.45 8.05 76.5 0 0 398.1224 407.3692   A 13.45 8.05 76.5 0 0 409.0898 418.5684   Z"></path>
	<path fill="#61ae39" d="   M 846.78 519.48   C 824.35 521.72 802.54 526.08 780.35 529.98   Q 788.75 492.36 797.44 455.00   Q 798.29 451.36 797.55 450.01   Q 794.97 445.29 790.46 447.45   Q 753.17 465.30 683.94 500.67   Q 679.74 502.82 677.43 505.98   Q 655.72 502.74 633.70 502.73   C 647.49 477.75 666.66 455.58 690.68 440.70   Q 712.02 427.47 734.22 420.50   C 752.98 414.61 771.07 409.82 800.42 402.11   Q 818.39 397.39 838.31 389.34   C 848.91 385.05 860.62 378.85 870.33 370.78   A 0.48 0.47 -20.0 0 1 871.11 371.14   Q 871.14 387.45 869.70 404.99   Q 869.03 413.22 868.26 423.09   C 867.66 430.82 866.33 437.57 865.56 444.64   Q 865.08 449.00 864.27 452.33   C 863.39 455.89 863.32 458.97 862.57 462.58   Q 859.95 475.15 859.63 476.63   Q 855.09 497.11 846.78 519.48   Z"></path>
	<path fill="#227ca0" d="   M 495.28 440.65   Q 496.15 445.06 497.06 449.23   Q 497.59 451.62 497.60 459.22   Q 494.27 477.98 477.29 486.25   Q 475.03 487.36 466.97 488.80   L 458.76 488.55   C 450.48 486.50 441.26 481.03 437.11 473.38   Q 434.59 468.72 434.49 468.54   C 431.11 462.25 431.15 453.50 431.95 446.88   Q 432.24 444.40 433.88 440.03   Q 435.14 436.69 436.19 433.24   Q 436.74 433.20 436.91 432.65   Q 438.03 429.00 439.57 426.40   Q 440.94 424.07 441.75 422.01   Q 449.53 409.34 459.52 396.02   C 462.62 391.88 466.45 392.41 469.33 396.43   C 480.26 411.74 488.54 424.99 495.28 440.65   Z   M 463.92 479.94   Q 467.98 479.98 469.19 479.61   C 488.78 473.54 491.89 453.93 483.58 437.32   Q 475.94 422.04 465.63 407.14   Q 465.19 406.51 464.71 406.51   Q 464.22 406.50 463.78 407.12   Q 453.15 421.80 445.18 436.91   C 436.52 453.34 439.20 473.01 458.66 479.49   Q 459.86 479.90 463.92 479.94   Z"></path>
	<path fill="#61ae39" d="   M 343.04 414.52   Q 341.93 423.14 343.09 430.26   Q 344.23 437.19 348.02 443.51   Q 359.72 463.04 381.92 466.24   Q 389.79 467.37 397.49 465.36   Q 399.36 464.87 400.85 464.83   A 2.83 2.73 31.2 0 0 401.86 464.60   Q 412.15 460.28 417.93 455.08   Q 425.00 448.72 428.51 439.12   Q 429.95 435.17 431.41 431.14   Q 433.91 431.54 436.19 433.24   Q 435.14 436.69 433.88 440.03   Q 432.24 444.40 431.95 446.88   C 431.15 453.50 431.11 462.25 434.49 468.54   Q 434.59 468.72 437.11 473.38   C 441.26 481.03 450.48 486.50 458.76 488.55   L 469.52 532.41   A 0.65 0.65 0.0 0 1 468.54 533.11   Q 449.41 520.79 429.45 510.31   C 426.90 508.97 425.25 507.56 423.53 505.02   Q 416.63 494.79 408.09 484.09   C 404.47 479.56 400.22 473.52 396.96 469.37   A 0.45 0.45 0.0 0 0 396.38 469.26   L 396.37 469.27   A 0.54 0.53 -30.0 0 0 396.17 469.99   Q 396.51 470.64 397.87 473.42   Q 402.55 483.00 413.15 499.61   Q 413.63 500.36 414.20 501.63   A 0.47 0.46 88.8 0 1 413.61 502.25   L 334.50 471.84   A 0.12 0.12 0.0 0 0 334.34 471.90   L 334.33 471.91   A 0.09 0.09 0.0 0 0 334.37 472.02   Q 350.07 481.13 387.72 500.78   Q 410.59 512.72 434.63 528.10   C 435.73 528.80 437.84 529.83 439.19 531.10   A 0.28 0.28 0.0 0 1 438.97 531.59   C 422.49 529.74 408.00 528.22 394.26 525.62   Q 389.42 524.71 386.50 524.21   Q 378.42 522.83 353.80 516.44   C 352.64 516.14 350.37 514.83 350.08 514.82   A 0.34 0.34 0.0 0 0 349.79 515.34   Q 350.30 516.14 351.32 516.48   Q 354.44 517.53 358.50 519.38   C 364.74 522.23 371.19 524.53 377.65 527.00   Q 355.06 528.46 333.00 532.15   Q 323.53 514.83 317.73 496.79   C 314.88 487.92 311.33 475.72 309.28 465.00   Q 306.01 447.91 305.57 443.49   C 304.53 433.02 303.13 424.96 302.75 415.81   Q 302.51 409.85 301.46 394.51   A 0.58 0.58 0.0 0 1 302.33 393.97   Q 321.23 405.40 342.24 412.81   Q 342.92 413.04 343.05 413.45   Q 343.19 413.87 343.04 414.52   Z"></path>
	<path fill="#62b8e2" d="   M 464.71 406.51   Q 465.19 406.51 465.63 407.14   Q 475.94 422.04 483.58 437.32   C 491.89 453.93 488.78 473.54 469.19 479.61   Q 467.98 479.98 463.92 479.94   Q 459.86 479.90 458.66 479.49   C 439.20 473.01 436.52 453.34 445.18 436.91   Q 453.15 421.80 463.78 407.12   Q 464.22 406.50 464.71 406.51   Z   M 479.7115 456.3039   A 9.17 6.01 75.4 0 0 483.2159 445.9151   A 9.17 6.01 75.4 0 0 475.0885 438.5561   A 9.17 6.01 75.4 0 0 471.5841 448.9449   A 9.17 6.01 75.4 0 0 479.7115 456.3039   Z"></path>
	<path fill="#216623" d="   M 441.75 422.01   Q 440.94 424.07 439.57 426.40   Q 438.03 429.00 436.91 432.65   Q 436.74 433.20 436.19 433.24   Q 433.91 431.54 431.41 431.14   C 432.54 425.95 432.25 422.40 432.12 417.70   L 441.75 422.01   Z"></path>
	<path fill="#61ae39" d="   M 559.79 548.75   L 530.20 552.27   Q 527.41 544.44 524.31 536.66   Q 516.03 515.90 504.46 497.92   A 0.20 0.19 30.4 0 0 504.10 498.01   Q 504.06 498.65 504.22 499.02   Q 520.43 535.49 526.88 576.12   Q 527.17 577.93 527.01 581.75   A 0.46 0.46 0.0 0 1 526.22 582.05   Q 505.88 561.37 482.73 543.72   A 7.61 7.55 9.8 0 1 480.11 540.03   L 463.83 489.94   A 0.59 0.58 -21.5 0 1 464.15 489.22   Q 465.33 488.70 466.97 488.80   Q 475.03 487.36 477.29 486.25   Q 494.27 477.98 497.60 459.22   Q 497.53 458.75 497.62 458.43   A 0.49 0.49 0.0 0 1 498.37 458.16   C 528.11 478.57 548.42 509.76 558.60 544.13   Q 559.27 546.41 559.79 548.75   Z"></path>
	<path fill="#519e34" d="   M 677.43 505.98   C 675.31 510.51 680.16 512.53 683.77 514.24   Q 694.30 519.22 704.78 524.09   Q 687.39 537.12 669.35 552.86   C 643.90 575.08 622.16 599.87 604.89 628.46   A 0.32 0.32 0.0 0 1 604.29 628.29   C 604.53 617.09 604.35 610.02 605.07 601.25   Q 607.82 568.06 618.66 536.39   Q 624.67 518.82 633.70 502.73   Q 655.72 502.74 677.43 505.98   Z"></path>
	<path fill="#519e34" d="   M 846.78 519.48   C 838.75 541.53 827.09 562.27 811.76 579.81   Q 795.33 598.61 773.99 610.82   Q 751.11 623.91 723.79 629.85   Q 721.05 630.45 720.67 630.53   Q 706.22 633.65 683.48 636.73   Q 659.60 639.96 639.78 647.08   Q 619.71 654.30 604.39 669.14   A 0.32 0.32 0.0 0 1 603.86 668.82   Q 605.20 664.56 607.52 659.53   Q 625.11 621.33 653.14 589.12   Q 679.00 559.39 710.52 535.35   A 2.72 2.72 0.0 0 1 712.97 534.91   L 721.61 537.54   Q 747.67 556.32 759.83 564.44   Q 761.59 565.61 764.77 566.21   C 769.78 567.15 772.44 564.07 773.55 559.28   Q 776.96 544.53 780.35 529.98   C 802.54 526.08 824.35 521.72 846.78 519.48   Z"></path>
	<path fill="#519e34" d="   M 333.00 532.15   Q 355.06 528.46 377.65 527.00   Q 402.22 535.35 421.12 539.12   Q 434.40 541.76 458.40 544.34   C 460.56 544.57 463.06 546.52 465.11 547.95   Q 482.87 560.27 504.17 578.82   Q 504.89 579.44 505.31 580.11   A 0.27 0.27 0.0 0 1 505.06 580.53   C 491.81 579.52 480.22 578.96 466.92 577.19   Q 443.30 574.04 432.51 571.94   Q 414.67 568.45 405.33 565.88   Q 391.26 562.00 388.04 561.20   Q 387.59 561.09 385.48 560.15   Q 385.26 560.06 385.04 560.14   Q 384.87 560.21 384.82 560.29   A 0.20 0.20 0.0 0 0 384.90 560.58   Q 393.49 564.85 395.38 565.61   Q 414.47 573.34 430.66 578.55   Q 436.86 580.54 448.86 583.67   C 467.08 588.43 484.81 591.08 504.62 593.41   Q 509.76 594.02 514.12 594.12   Q 516.79 594.17 518.47 595.79   C 535.40 612.00 550.14 633.24 559.02 656.00   Q 559.63 657.56 559.53 658.67   A 0.36 0.36 0.0 0 1 558.95 658.91   C 545.71 648.16 529.99 641.49 513.75 637.30   Q 496.19 632.76 464.59 626.34   C 448.68 623.11 431.31 617.56 416.65 610.79   Q 362.36 585.72 333.00 532.15   Z"></path>
	<path fill="#519e34" d="   M 530.20 552.27   L 559.79 548.75   Q 562.36 556.60 563.66 564.72   Q 564.81 571.91 565.37 574.59   Q 566.40 579.55 566.74 584.50   Q 567.67 598.28 567.92 610.97   C 568.05 617.94 566.81 624.93 566.92 632.06   A 0.62 0.61 -61.4 0 1 565.78 632.40   Q 555.19 615.24 541.10 599.27   A 5.55 5.51 -68.9 0 1 539.76 596.22   Q 537.33 574.14 530.20 552.27   Z"></path>
</svg>
SVG);

if(isset($this) === false or ($this instanceof Client) === false){
	throw new \LogicException('login.php must be included from Client::start()');
}

Logging::$hide = true;

$step = $this->load->step;

try {
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if($step === Authentication::NEED_AUTHENTICATION and isset($_POST['send_code'])){
			if(isset($_POST['login_method'])){
				if($_POST['login_method'] === 'token' and isset($_POST['token']) and str_contains($_POST['token'],chr(58))){
					$token = trim($_POST['token']);
					$this->sign_in(bot_token : $token);
				} elseif($_POST['login_method'] === 'phone' and isset($_POST['phone']) and isset($_POST['country_code'])){
					$phone_number = preg_replace('/[^\d]/',strval(null),$_POST['country_code'].$_POST['phone']);
					$this->send_code(phone_number : $phone_number);
				}
			}
		} elseif($step === Authentication::NEED_CODE and isset($_POST['code'])){
			$code = trim($_POST['code']);
			try {
				$authorization = $this->sign_in(code : $code);
				if($authorization instanceof \Tak\Liveproto\Tl\Types\Auth\AuthorizationSignUpRequired):
					$this->sign_up(first_name : 'Live',last_name : 'Proto');
				endif;
			} catch(\Throwable $e){
				if($e->getMessage() !== 'SESSION_PASSWORD_NEEDED'){
					throw $e;
				}
			}
		} elseif($step === Authentication::NEED_PASSWORD and isset($_POST['password'])){
			$password = trim($_POST['password']);
			$this->sign_in(password : $password);
		}
	}
} catch(\Throwable $e){
	$error = $e->getMessage();
}

$stage = $this->load->step->value;

@ob_clean();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>Secure Login System</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		:root {
			--primary: #6366f1;
			--primary-dark: #4f46e5;
			--bg: #f9fafb;
			--card-bg: #ffffff;
			--text: #111827;
			--text-light: #6b7280;
			--error: #ef4444;
			--success: #10b981;
			--failed: #dbde28;
		}
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Segoe UI',system-ui,sans-serif;
		}
		body {
			background: var(--bg);
			min-height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 20px;
			background-image: radial-gradient(#d1d5db 1px,transparent 1px);
			background-size: 20px 20px;
		}
		.login-container {
			width: 100%;
			max-width: 420px;
			position: relative;
			z-index: 10;
		}
		.login-card {
			background: var(--card-bg);
			border-radius: 24px;
			padding: 40px 30px;
			box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
			position: relative;
			overflow: hidden;
			transform: translateY(0);
			transition: transform 0.4s ease,box-shadow 0.4s ease;
		}
		.login-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 30px 60px -10px rgba(0,0,0,0.2);
		}
		.rgb-border {
			position: absolute;
			top: -3px;
			left: -3px;
			right: -3px;
			bottom: -3px;
			background: var(--rgb-border);
			background-size: 300% 300%;
			border-radius: 27px;
			z-index: -1;
			animation: rgb-animation 3s linear infinite;
			filter: blur(3px);
			opacity: 0.7;
		}
		.logo-container {
			text-align: center;
			margin-bottom: 30px;
			animation: float 4s ease-in-out infinite;
		}
		h1 {
			text-align: center;
			color: var(--text);
			margin-bottom: 10px;
			font-weight: 700;
			font-size: 28px;
			letter-spacing: -0.5px;
		}
		.subtitle {
			text-align: center;
			color: var(--text-light);
			margin-bottom: 32px;
			font-size: 16px;
			line-height: 1.5;
		}
		.step-indicator {
			display: flex;
			justify-content: center;
			gap: 12px;
			margin-bottom: 30px;
		}
		.step {
			width: 12px;
			height: 12px;
			border-radius: 50%;
			background: #e5e7eb;
			transition: all 0.3s ease;
		}
		.step.active {
			background: var(--primary);
			transform: scale(1.2);
		}
		.form-group {
			margin-bottom: 24px;
			position: relative;
		}
		.input-label {
			display: block;
			margin-bottom: 8px;
			color: var(--text);
			font-weight: 500;
			font-size: 14px;
			display: flex;
			align-items: center;
		}
		.input-label i {
			margin-right: 8px;
			color: var(--primary);
		}
		.input-field {
			width: 100%;
			padding: 16px 20px;
			border: 2px solid #e5e7eb;
			border-radius: 16px;
			font-size: 16px;
			transition: all 0.3s ease;
			outline: none;
			background: var(--card-bg);
			color: var(--text);
			box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
		}
		.input-field:focus {
			border-color: var(--primary);
			box-shadow: 0 0 0 4px rgba(99,102,241,0.2);
			transform: translateY(-2px);
		}
		.input-field::placeholder {
			color: #9ca3af;
		}
		.phone-group {
			display: flex;
			gap: 10px;
		}
		.country-code {
			flex: 0 0 80px;
		}
		.phone-number {
			flex: 1;
		}
		.method-selector {
			display: flex;
			gap: 12px;
			margin-bottom: 24px;
		}
		.method-btn {
			flex: 1;
			padding: 16px;
			background: #f3f4f6;
			border: none;
			border-radius: 16px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
		}
		.method-btn i {
			font-size: 24px;
			margin-bottom: 8px;
			color: var(--primary);
		}
		.method-btn:hover {
			background: #e5e7eb;
			transform: translateY(-3px);
		}
		.method-btn.active {
			background: var(--primary);
			color: white;
			box-shadow: 0 10px 20px -10px rgba(79,70,229,0.4);
		}
		.method-btn.active i {
			color: white;
		}
		.submit-btn {
			width: 100%;
			padding: 18px;
			background: var(--primary);
			color: white;
			border: none;
			border-radius: 16px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			box-shadow: 0 10px 20px -10px rgba(79,70,229,0.5);
			position: relative;
			overflow: hidden;
		}
		.submit-btn::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg,transparent,rgba(255,255,255,0.2),transparent);
			transition: 0.5s;
		}
		.submit-btn:hover {
			transform: translateY(-3px);
			box-shadow: 0 15px 25px -10px rgba(79,70,229,0.6);
			background: var(--primary-dark);
		}
		.submit-btn:hover::before {
			left: 100%;
		}
		.submit-btn:active {
			transform: translateY(1px);
		}
		.back-btn {
			display: block;
			text-align: center;
			margin-top: 20px;
			color: var(--primary);
			font-weight: 600;
			text-decoration: none;
			cursor: pointer;
			transition: all 0.3s ease;
		}
		.back-btn:hover {
			color: var(--primary-dark);
			transform: translateX(-3px);
		}
		.back-btn i {
			margin-right: 5px;
		}
		.success-message {
			text-align: center;
			padding: 40px 0;
			animation: fadeIn 0.6s ease;
		}
		.success-icon {
			font-size: 80px;
			color: var(--success);
			margin-bottom: 20px;
			animation: scaleIn 0.5s ease,pulse 2s infinite;
		}
		.failed-message {
			text-align: center;
			padding: 40px 0;
			animation: fadeIn 0.6s ease;
		}
		.failed-icon {
			font-size: 80px;
			color: var(--failed);
			margin-bottom: 20px;
			animation: scaleIn 0.5s ease,pulse 2s infinite;
		}
		.error-message {
			background: rgba(239,68,68,0.1);
			color: var(--error);
			padding: 16px;
			border-radius: 12px;
			margin-bottom: 24px;
			text-align: center;
			animation: shake 0.5s ease;
			border: 1px solid rgba(239,68,68,0.2);
		}
		.password-toggle {
			position: absolute;
			right: 16px;
			top: 42px;
			color: var(--text-light);
			cursor: pointer;
		}
		@keyframes rgb-animation {
			0% { background-position: 0% 50%; }
			100% { background-position: 300% 50%; }
		}
		@keyframes float {
			0% { transform: translateY(0px); }
			50% { transform: translateY(-10px); }
			100% { transform: translateY(0px); }
		}
		@keyframes scaleIn {
			0% { transform: scale(0); opacity: 0; }
			100% { transform: scale(1); opacity: 1; }
		}
		@keyframes pulse {
			0% { transform: scale(1); }
			50% { transform: scale(1.05); }
			100% { transform: scale(1); }
		}
		@keyframes fadeIn {
			from { opacity: 0; transform: translateY(20px); }
			to { opacity: 1; transform: translateY(0); }
		}
		@keyframes shake {
			0%,100% { transform: translateX(0); }
			20%,60% { transform: translateX(-8px); }
			40%,80% { transform: translateX(8px); }
		}
		.particles {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 1;
			pointer-events: none;
		}
		.particle {
			position: absolute;
			border-radius: 50%;
			background: var(--primary);
			opacity: 0.3;
			animation: float-particle 15s infinite linear;
		}
		@keyframes float-particle {
			0% { transform: translateY(0) translateX(0) rotate(0deg); }
			100% { transform: translateY(-100vh) translateX(100px) rotate(360deg); }
		}
	</style>
</head>
<body>
	<div class="particles" id="particles"></div>
	<div class="login-container">
		<div class="login-card">
			<div class="rgb-border"></div>
			<?php if(isset($error)): ?>
				<div class="error-message">
					<i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
				</div>
			<?php endif; ?>
			<?php if($stage === 0): ?>
				<div class="logo-container">
					<?= LOGO_SVG ?>
				</div>
				<h1>Welcome</h1>
				<p class="subtitle">Choose your preferred login method to continue</p>
				<form method="POST">
					<div class="method-selector">
						<button type="button" class="method-btn" id="tokenBtn">
							<i class="fas fa-key"></i>
							Token
						</button>
						<button type="button" class="method-btn" id="phoneBtn">
							<i class="fas fa-mobile-alt"></i>
							Phone
						</button>
					</div>
					<input type="hidden" name="login_method" id="loginMethod" value="token">
					<div id="tokenField">
						<div class="form-group">
							<label class="input-label"><i class="fas fa-fingerprint"></i> Your Token</label>
							<input type="text" class="input-field" name="token" placeholder="123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11" minlength="35" maxlength="46">
						</div>
					</div>
					<div id="phoneField" style="display: none;">
						<div class="form-group">
							<label class="input-label"><i class="fas fa-phone"></i> Phone Number</label>
							<div class="phone-group">
								<div class="country-code">
									<input type="text" class="input-field" name="country_code" placeholder="+1" value="+1">
								</div>
								<div class="phone-number">
									<input type="tel" class="input-field" name="phone" placeholder="Enter phone number" pattern="[0-9]{5,15}">
								</div>
							</div>
						</div>
					</div>
					<button type="submit" name="send_code" class="submit-btn">Continue</button>
				</form>
			<?php elseif($stage === 1): ?>
				<div class="logo-container">
					<?= LOGO_SVG ?>
				</div>
				<h1>Verification Code</h1>
				<p class="subtitle">
					We sent a 5-digit code to 
					<?= htmlspecialchars($this->load->phonenumber) ?>
				</p>
				<div class="step-indicator">
					<div class="step active"></div>
					<div class="step active"></div>
					<div class="step"></div>
				</div>
				<form method="POST">
					<div class="form-group">
						<label class="input-label"><i class="fas fa-shield-alt"></i> Verification Code</label>
						<input type="text" class="input-field" name="code" placeholder="Enter 5-digit code" pattern="[0-9]{5}" maxlength="5" required autofocus>
					</div>
					<button type="submit" name="sign_in" class="submit-btn">Verify Code</button>
					<a class="back-btn" onclick="history.back()"><i class="fas fa-arrow-left"></i> Back</a>
				</form>
			<?php elseif($stage === 5): ?>
				<div class="logo-container">
					<?= LOGO_SVG ?>
				</div>
				<h1>Two-Factor Authentication</h1>
				<p class="subtitle">Enter your 2FA password for enhanced security</p>
				<div class="step-indicator">
					<div class="step active"></div>
					<div class="step active"></div>
					<div class="step active"></div>
				</div>
				<form method="POST">
					<div class="form-group">
						<label class="input-label"><i class="fas fa-lock"></i> 2FA Password</label>
						<input type="password" class="input-field" id="passwordInput" name="password" placeholder="Enter your password" required autofocus>
						<span class="password-toggle" id="passwordToggle">
							<i class="fas fa-eye"></i>
						</span>
					</div>
					<button type="submit" class="submit-btn">Complete Login</button>
					<a class="back-btn" onclick="history.back()"><i class="fas fa-arrow-left"></i> Back</a>
				</form>
			<?php elseif($stage === 7): ?>
				<div class="success-message">
					<div class="success-icon">
						<i class="fas fa-check-circle"></i>
					</div>
					<h1>Login Successful !</h1>
					<p class="subtitle">Your bot is now running...</p>
				</div>
			<?php else: ?>
				<div class="failed-message">
					<div class="failed-icon">
						<i class="fas fa-exclamation-triangle"></i>
					</div>
					<h1>Login Failed !</h1>
					<p class="subtitle">Web login does not support the stage your account is logged into ( Stage : <?= $stage ?> )</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<script>
		const tokenBtn = document.getElementById('tokenBtn');
		if(tokenBtn){
			tokenBtn.addEventListener('click',function(){
				this.classList.add('active');
				document.getElementById('phoneBtn').classList.remove('active');
				document.getElementById('loginMethod').value = 'token';
				document.getElementById('tokenField').style.display = 'block';
				document.getElementById('phoneField').style.display = 'none';
			});
			tokenBtn.classList.add('active');
		}

		const phoneBtn = document.getElementById('phoneBtn');
		if(phoneBtn){
			phoneBtn.addEventListener('click',function(){
				this.classList.add('active');
				document.getElementById('tokenBtn').classList.remove('active');
				document.getElementById('loginMethod').value = 'phone';
				document.getElementById('tokenField').style.display = 'none';
				document.getElementById('phoneField').style.display = 'block';
			});
		}

		const passwordInput = document.getElementById('passwordInput');
		const passwordToggle = document.getElementById('passwordToggle');
		if(passwordInput && passwordToggle){
			passwordToggle.addEventListener('click',function(){
				if(passwordInput.type === 'password'){
					passwordInput.type = 'text';
					passwordToggle.innerHTML = '<i class="fas fa-eye-slash"></i>';
				} else {
					passwordInput.type = 'password';
					passwordToggle.innerHTML = '<i class="fas fa-eye"></i>';
				}
			});
		}

		const codeInput = document.querySelector('input[name="code"]');
		if(codeInput){
			codeInput.addEventListener('input',function(){
				if(this.value.length === 5){
					this.form.submit();
				}
			});
		}

		function createParticles(){
			const particlesContainer = document.getElementById('particles');
			const particleCount = 20;
			for(let i = 0; i < particleCount; i++){
				const particle = document.createElement('div');
				particle.classList.add('particle');
				const size = Math.random() * 20 + 5;
				const posX = Math.random() * 100;
				const posY = Math.random() * 100;
				const animationDuration = Math.random() * 20 + 10;
				const animationDelay = Math.random() * 5;
				const hue = Math.floor(Math.random() * 360);
				particle.style.width = `${size}px`;
				particle.style.height = `${size}px`;
				particle.style.left = `${posX}%`;
				particle.style.top = `${posY}%`;
				particle.style.background = `hsl(${hue},70%,60%)`;
				particle.style.animationDuration = `${animationDuration}s`;
				particle.style.animationDelay = `-${animationDelay}s`;
				particlesContainer.appendChild(particle);
			}
		}
		window.addEventListener('load',createParticles);
	</script>
</body>
</html>
<?php

if($stage < 7){
	ob_start();
	$this->stop();
	ob_end_clean();
	die();
}

?>